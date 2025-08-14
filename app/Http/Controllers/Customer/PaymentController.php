<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\CustomerBooking;
use App\Models\Customer\PaymentRecord;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(CustomerBooking $booking)
    {
        $this->authorize('view', $booking);
        
        if ($booking->status === 'confirmed') {
            return redirect()->route('customer.bookings.show', $booking)
                ->with('info', 'This booking is already confirmed.');
        }
        
        return view('customer.payments.gateway', compact('booking'));
    }
    
    public function process(Request $request, CustomerBooking $booking)
    {
        $this->authorize('view', $booking);
        
        $validated = $request->validate([
            'card_number' => 'required|string|size:16',
            'card_holder_name' => 'required|string|max:255',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|size:4',
            'cvv' => 'required|string|size:3',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_zip' => 'required|string',
        ]);
        
        // Simulate payment processing (dummy gateway)
        $paymentSuccess = $this->simulatePaymentProcessing($validated);
        
        if ($paymentSuccess) {
            // Create payment record
            $payment = PaymentRecord::create([
                'member_id' => auth()->id(),
                'booking_id' => $booking->id,
                'amount' => $booking->price,
                'payment_method' => 'credit_card',
                'transaction_id' => PaymentRecord::generateTransactionId(),
                'status' => 'completed',
                'payment_date' => now(),
                'description' => "Payment for {$booking->session_type} session with {$booking->trainer->name}",
                'card_last_four' => substr($validated['card_number'], -4),
                'card_type' => $this->detectCardType($validated['card_number']),
            ]);
            
            // Update booking status
            $booking->update(['status' => 'confirmed']);
            
            return redirect()->route('customer.payments.success', $payment)
                ->with('success', 'Payment processed successfully! Your booking is confirmed.');
        } else {
            return back()
                ->withInput()
                ->with('error', 'Payment failed. Please check your card details and try again.');
        }
    }
    
    public function success(PaymentRecord $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['booking.trainer']);
        return view('customer.payments.success', compact('payment'));
    }
    
    public function history()
    {
        $member = auth()->user();
        $payments = PaymentRecord::where('member_id', $member->id)
            ->with(['booking.trainer'])
            ->latest()
            ->paginate(15);
        
        return view('customer.payments.history', compact('payments'));
    }
    
    public function receipt(PaymentRecord $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['booking.trainer', 'member']);
        return view('customer.payments.receipt', compact('payment'));
    }
    
    private function simulatePaymentProcessing($cardData)
    {
        // Simulate payment processing with 95% success rate
        // In a real application, this would integrate with a payment gateway like Stripe, PayPal, etc.
        
        // Simulate some processing time
        usleep(500000); // 0.5 seconds
        
        // Fail if card number starts with '4000' (for testing)
        if (substr($cardData['card_number'], 0, 4) === '4000') {
            return false;
        }
        
        // Otherwise, simulate success
        return rand(1, 100) <= 95;
    }
    
    private function detectCardType($cardNumber)
    {
        $firstDigit = substr($cardNumber, 0, 1);
        $firstTwoDigits = substr($cardNumber, 0, 2);
        
        if ($firstDigit === '4') {
            return 'Visa';
        } elseif (in_array($firstTwoDigits, ['51', '52', '53', '54', '55'])) {
            return 'MasterCard';
        } elseif (in_array($firstTwoDigits, ['34', '37'])) {
            return 'American Express';
        } elseif ($firstTwoDigits === '60') {
            return 'Discover';
        } else {
            return 'Unknown';
        }
    }
    
    public function webhook(Request $request)
    {
        // Handle payment gateway webhooks
        // This would be used for real payment gateway integrations
        
        $payload = $request->all();
        
        // Verify webhook signature (implementation depends on payment provider)
        // Process the webhook data
        
        return response()->json(['status' => 'success']);
    }
}