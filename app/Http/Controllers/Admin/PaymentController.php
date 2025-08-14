<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer\PaymentRecord;
use App\Models\User;
use App\Models\Customer\CustomerBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = PaymentRecord::with(['member', 'booking.trainer']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $payments = $query->latest()->paginate(20);
        
        $stats = [
            'total_payments' => PaymentRecord::completed()->sum('amount'),
            'monthly_revenue' => PaymentRecord::thisMonth()->completed()->sum('amount'),
            'today_revenue' => PaymentRecord::today()->completed()->sum('amount'),
            'pending_payments' => PaymentRecord::where('status', 'pending')->count(),
        ];
        
        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        $bookings = CustomerBooking::with(['member', 'trainer'])->get();
        
        return view('admin.payments.create', compact('members', 'bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:trainer_bookings,id',
            'amount' => 'required|numeric|min:0.01|max:99999.99',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,cash,bank_transfer',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'card_last_four' => 'nullable|string|size:4',
            'card_type' => 'nullable|string|max:50',
        ]);

        // Generate unique transaction ID
        $validated['transaction_id'] = PaymentRecord::generateTransactionId();

        PaymentRecord::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment record created successfully.');
    }
    
    public function show(PaymentRecord $payment)
    {
        $payment->load(['member', 'booking.trainer']);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit(PaymentRecord $payment)
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        $bookings = CustomerBooking::with(['member', 'trainer'])->get();
        
        return view('admin.payments.edit', compact('payment', 'members', 'bookings'));
    }

    public function update(Request $request, PaymentRecord $payment)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:users,id',
            'booking_id' => 'nullable|exists:trainer_bookings,id',
            'amount' => 'required|numeric|min:0.01|max:99999.99',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,cash,bank_transfer',
            'status' => 'required|in:pending,completed,failed,refunded',
            'payment_date' => 'required|date',
            'description' => 'nullable|string|max:1000',
            'card_last_four' => 'nullable|string|size:4',
            'card_type' => 'nullable|string|max:50',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment record updated successfully.');
    }

    public function destroy(PaymentRecord $payment)
    {
        $transactionId = $payment->transaction_id;
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', "Payment record '{$transactionId}' deleted successfully.");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,mark_completed,mark_failed,mark_refunded',
            'payment_ids' => 'required|json'
        ]);

        $paymentIds = json_decode($request->payment_ids);
        $action = $request->action;

        if (empty($paymentIds)) {
            return redirect()->route('admin.payments.index')
                ->with('error', 'No payments selected.');
        }

        $payments = PaymentRecord::whereIn('id', $paymentIds)->get();
        $count = $payments->count();

        switch ($action) {
            case 'mark_completed':
                $payments->each(function ($payment) {
                    $payment->update(['status' => 'completed']);
                });
                $message = "{$count} payments marked as completed.";
                break;

            case 'mark_failed':
                $payments->each(function ($payment) {
                    $payment->update(['status' => 'failed']);
                });
                $message = "{$count} payments marked as failed.";
                break;

            case 'mark_refunded':
                $payments->each(function ($payment) {
                    $payment->update(['status' => 'refunded']);
                });
                $message = "{$count} payments marked as refunded.";
                break;

            case 'delete':
                $payments->each(function ($payment) {
                    $payment->delete();
                });
                $message = "{$count} payment records deleted successfully.";
                break;
        }

        return redirect()->route('admin.payments.index')
            ->with('success', $message);
    }
    
    public function exportCsv(Request $request)
    {
        $query = PaymentRecord::with(['member', 'booking.trainer']);
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $payments = $query->latest()->get();
        
        $csvData = [];
        $csvData[] = [
            'Transaction ID',
            'Member Name',
            'Member Email',
            'Trainer Name',
            'Amount',
            'Payment Method',
            'Status',
            'Payment Date',
            'Description'
        ];
        
        foreach ($payments as $payment) {
            $csvData[] = [
                $payment->transaction_id,
                $payment->member->name,
                $payment->member->email,
                $payment->booking->trainer->name ?? 'N/A',
                $payment->amount,
                $payment->payment_method,
                $payment->status,
                $payment->payment_date->format('Y-m-d H:i:s'),
                $payment->description
            ];
        }
        
        $filename = 'payments_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    public function generateReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        $payments = PaymentRecord::with(['member', 'booking.trainer'])
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo)
            ->completed()
            ->get();
        
        $stats = [
            'total_amount' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'average_transaction' => $payments->avg('amount'),
            'payment_methods' => $payments->groupBy('payment_method')->map->count(),
            'daily_totals' => $payments->groupBy(function ($payment) {
                return $payment->created_at->format('Y-m-d');
            })->map->sum('amount'),
        ];
        
        return view('admin.payments.report', compact('payments', 'stats', 'dateFrom', 'dateTo'));
    }
}