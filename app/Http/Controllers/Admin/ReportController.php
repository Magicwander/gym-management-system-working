<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Member;
use App\Models\Admin\Trainer;
use App\Models\Customer\CustomerBooking;
use App\Models\Customer\PaymentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function memberReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        $members = Member::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['bookings', 'payments'])
            ->get();
        
        $stats = [
            'total_members' => $members->count(),
            'active_members' => $members->where('is_active', true)->count(),
            'total_bookings' => $members->sum(function ($member) {
                return $member->bookings->count();
            }),
            'total_spent' => $members->sum(function ($member) {
                return $member->payments->where('status', 'completed')->sum('amount');
            }),
        ];
        
        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.members-pdf', compact('members', 'stats', 'dateFrom', 'dateTo'));
            return $pdf->download('member-report-' . now()->format('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.members', compact('members', 'stats', 'dateFrom', 'dateTo'));
    }
    
    public function trainerReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        $trainers = Trainer::with(['bookings' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }])->get();
        
        $stats = [
            'total_trainers' => $trainers->count(),
            'active_trainers' => $trainers->where('is_active', true)->count(),
            'total_bookings' => $trainers->sum(function ($trainer) {
                return $trainer->bookings->count();
            }),
            'total_earnings' => $trainers->sum(function ($trainer) {
                return $trainer->bookings->sum('price');
            }),
        ];
        
        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.trainers-pdf', compact('trainers', 'stats', 'dateFrom', 'dateTo'));
            return $pdf->download('trainer-report-' . now()->format('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.trainers', compact('trainers', 'stats', 'dateFrom', 'dateTo'));
    }
    
    public function bookingReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        $bookings = CustomerBooking::with(['member', 'trainer', 'payment'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        
        $stats = [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'total_revenue' => $bookings->sum('price'),
            'status_breakdown' => $bookings->groupBy('status')->map->count(),
        ];
        
        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.bookings-pdf', compact('bookings', 'stats', 'dateFrom', 'dateTo'));
            return $pdf->download('booking-report-' . now()->format('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.bookings', compact('bookings', 'stats', 'dateFrom', 'dateTo'));
    }
    
    public function revenueReport(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        $payments = PaymentRecord::with(['member', 'booking.trainer'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->completed()
            ->get();
        
        $stats = [
            'total_revenue' => $payments->sum('amount'),
            'total_transactions' => $payments->count(),
            'average_transaction' => $payments->avg('amount'),
            'payment_methods' => $payments->groupBy('payment_method')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('amount')
                ];
            }),
            'daily_revenue' => $payments->groupBy(function ($payment) {
                return $payment->created_at->format('Y-m-d');
            })->map->sum('amount')->sortKeys(),
        ];
        
        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.reports.revenue-pdf', compact('payments', 'stats', 'dateFrom', 'dateTo'));
            return $pdf->download('revenue-report-' . now()->format('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.revenue', compact('payments', 'stats', 'dateFrom', 'dateTo'));
    }
    
    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'payments');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());
        
        switch ($type) {
            case 'members':
                return $this->exportMembersCsv($dateFrom, $dateTo);
            case 'trainers':
                return $this->exportTrainersCsv($dateFrom, $dateTo);
            case 'bookings':
                return $this->exportBookingsCsv($dateFrom, $dateTo);
            case 'payments':
            default:
                return $this->exportPaymentsCsv($dateFrom, $dateTo);
        }
    }
    
    private function exportMembersCsv($dateFrom, $dateTo)
    {
        $members = Member::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with(['bookings', 'payments'])
            ->get();
        
        $csvData = [
            ['Name', 'Email', 'Phone', 'Join Date', 'Status', 'Total Bookings', 'Total Spent']
        ];
        
        foreach ($members as $member) {
            $csvData[] = [
                $member->name,
                $member->email,
                $member->phone,
                $member->created_at->format('Y-m-d'),
                $member->is_active ? 'Active' : 'Inactive',
                $member->bookings->count(),
                $member->payments->where('status', 'completed')->sum('amount')
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'members-report');
    }
    
    private function exportTrainersCsv($dateFrom, $dateTo)
    {
        $trainers = Trainer::with(['bookings' => function ($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('created_at', [$dateFrom, $dateTo]);
        }])->get();
        
        $csvData = [
            ['Name', 'Email', 'Phone', 'Specialization', 'Experience', 'Hourly Rate', 'Total Bookings', 'Total Earnings']
        ];
        
        foreach ($trainers as $trainer) {
            $csvData[] = [
                $trainer->name,
                $trainer->email,
                $trainer->phone,
                $trainer->specialization,
                $trainer->experience_years . ' years',
                '$' . $trainer->hourly_rate,
                $trainer->bookings->count(),
                '$' . $trainer->bookings->sum('price')
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'trainers-report');
    }
    
    private function exportBookingsCsv($dateFrom, $dateTo)
    {
        $bookings = CustomerBooking::with(['member', 'trainer'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        
        $csvData = [
            ['Booking Date', 'Member', 'Trainer', 'Session Type', 'Price', 'Status', 'Created At']
        ];
        
        foreach ($bookings as $booking) {
            $csvData[] = [
                $booking->booking_date->format('Y-m-d'),
                $booking->member->name,
                $booking->trainer->name,
                $booking->session_type,
                '$' . $booking->price,
                $booking->status,
                $booking->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'bookings-report');
    }
    
    private function exportPaymentsCsv($dateFrom, $dateTo)
    {
        $payments = PaymentRecord::with(['member', 'booking.trainer'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
        
        $csvData = [
            ['Transaction ID', 'Member', 'Trainer', 'Amount', 'Payment Method', 'Status', 'Payment Date']
        ];
        
        foreach ($payments as $payment) {
            $csvData[] = [
                $payment->transaction_id,
                $payment->member->name,
                $payment->booking->trainer->name ?? 'N/A',
                '$' . $payment->amount,
                $payment->payment_method,
                $payment->status,
                $payment->payment_date->format('Y-m-d H:i:s')
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'payments-report');
    }
    
    private function generateCsvResponse($data, $filename)
    {
        $handle = fopen('php://temp', 'w+');
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }
}