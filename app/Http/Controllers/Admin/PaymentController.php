<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
    
    public function show(PaymentRecord $payment)
    {
        $payment->load(['member', 'booking.trainer']);
        return view('admin.payments.show', compact('payment'));
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