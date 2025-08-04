<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;

class PaymentGatewayController extends Controller
{
    /**
     * Show payment gateway page
     */
    public function show(Request $request)
    {
        $paymentData = $request->all();
        
        // Validate required payment data
        if (!isset($paymentData['membership_id']) || !isset($paymentData['amount'])) {
            return redirect()->route('home')->with('error', 'Invalid payment request.');
        }

        $membership = Membership::with('user')->findOrFail($paymentData['membership_id']);

        return view('frontend.payment-gateway', compact('paymentData', 'membership'));
    }

    /**
     * Process payment (mock implementation)
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer',
            'card_number' => 'required_if:payment_method,credit_card,debit_card|string|min:16|max:19',
            'expiry_month' => 'required_if:payment_method,credit_card,debit_card|string|size:2',
            'expiry_year' => 'required_if:payment_method,credit_card,debit_card|string|size:4',
            'cvv' => 'required_if:payment_method,credit_card,debit_card|string|size:3',
            'cardholder_name' => 'required_if:payment_method,credit_card,debit_card|string|max:255',
            'bank_name' => 'required_if:payment_method,bank_transfer|string|max:255',
            'account_number' => 'required_if:payment_method,bank_transfer|string|max:50',
        ]);

        $membership = Membership::findOrFail($validated['membership_id']);

        // Mock payment processing - in real implementation, integrate with actual payment gateway
        $paymentSuccess = $this->mockPaymentProcessing($validated);

        if ($paymentSuccess) {
            // Activate membership and user account
            $membership->update([
                'status' => 'active',
                'notes' => 'Payment completed successfully via ' . $validated['payment_method']
            ]);
            
            $membership->user->update(['is_active' => true]);

            // Create payment record (you might want to create a payments table)
            // Payment::create([...]);

            return redirect()->route('enrollment.payment.success', ['membership' => $membership->id]);
        } else {
            return back()->withErrors(['payment' => 'Payment processing failed. Please try again.'])->withInput();
        }
    }

    /**
     * Mock payment processing
     * In real implementation, this would integrate with actual payment gateway APIs
     */
    private function mockPaymentProcessing($paymentData)
    {
        // Simulate payment processing delay
        sleep(2);

        // Mock success/failure (90% success rate for demo)
        $random = rand(1, 10);
        
        // For demo purposes, always succeed if card number ends with even digit
        if (isset($paymentData['card_number'])) {
            $lastDigit = substr($paymentData['card_number'], -1);
            return ($lastDigit % 2 === 0);
        }

        // For bank transfer, always succeed
        if ($paymentData['payment_method'] === 'bank_transfer') {
            return true;
        }

        return $random <= 9; // 90% success rate
    }

    /**
     * Handle payment webhook (for real payment gateways)
     */
    public function webhook(Request $request)
    {
        // This would handle webhooks from real payment gateways
        // like Stripe, PayPal, etc.
        
        $payload = $request->all();
        
        // Verify webhook signature (implementation depends on payment gateway)
        // Process payment status updates
        // Update membership status accordingly
        
        return response()->json(['status' => 'success']);
    }
}
