<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Show enrollment form
     */
    public function create()
    {
        return view('frontend.enrollment');
    }

    /**
     * Process enrollment and redirect to payment gateway
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'age' => 'required|integer|min:12|max:100',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'membership_type' => 'required|in:platinum,gold,silver',
            'membership_duration' => 'required|in:3_months,6_months,1_year',
            'payment_method' => 'required|in:credit_card,paypal,stripe',
        ]);

        try {
            DB::beginTransaction();

            // Calculate membership price based on type and duration
            $price = $this->calculateMembershipPrice($validated['membership_type'], $validated['membership_duration']);

            // Calculate date of birth from age
            $dateOfBirth = Carbon::now()->subYears($validated['age'])->format('Y-m-d');

            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('password123'), // Default password, user can change later
                'role' => 'member',
                'phone' => $validated['phone'],
                'date_of_birth' => $dateOfBirth,
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'is_active' => false, // Will be activated after payment
            ]);

            // Calculate membership dates
            $startDate = Carbon::now();
            $endDate = $this->calculateEndDate($startDate, $validated['membership_duration']);

            // Create membership record (pending payment)
            $membership = Membership::create([
                'user_id' => $user->id,
                'type' => $validated['membership_type'],
                'duration' => $validated['membership_duration'],
                'price' => $price,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'pending', // Will be activated after payment
                'notes' => 'Enrollment pending payment confirmation',
            ]);

            DB::commit();

            // Redirect to payment gateway based on selected method
            return $this->redirectToPaymentGateway($membership, $validated['payment_method']);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Enrollment failed. Please try again.'])->withInput();
        }
    }

    /**
     * Calculate membership price based on type and duration
     */
    private function calculateMembershipPrice($type, $duration)
    {
        $basePrices = [
            'silver' => 2000,   // Rs 2000 per month
            'gold' => 3500,     // Rs 3500 per month
            'platinum' => 5000, // Rs 5000 per month
        ];

        $multipliers = [
            '3_months' => 3,
            '6_months' => 6,
            '1_year' => 12,
        ];

        $basePrice = $basePrices[$type];
        $months = $multipliers[$duration];

        // Apply discounts for longer durations
        $discount = 0;
        if ($duration === '6_months') {
            $discount = 0.05; // 5% discount
        } elseif ($duration === '1_year') {
            $discount = 0.10; // 10% discount
        }

        $totalPrice = $basePrice * $months;
        $discountAmount = $totalPrice * $discount;

        return $totalPrice - $discountAmount;
    }

    /**
     * Calculate membership end date
     */
    private function calculateEndDate($startDate, $duration)
    {
        switch ($duration) {
            case '3_months':
                return $startDate->copy()->addMonths(3);
            case '6_months':
                return $startDate->copy()->addMonths(6);
            case '1_year':
                return $startDate->copy()->addYear();
            default:
                return $startDate->copy()->addMonths(3);
        }
    }

    /**
     * Redirect to appropriate payment gateway
     */
    private function redirectToPaymentGateway($membership, $paymentMethod)
    {
        $paymentData = [
            'membership_id' => $membership->id,
            'amount' => $membership->price,
            'currency' => 'PKR',
            'description' => ucfirst($membership->type) . ' Membership - ' . str_replace('_', ' ', $membership->duration),
            'customer_email' => $membership->user->email,
            'customer_name' => $membership->user->name,
        ];

        switch ($paymentMethod) {
            case 'stripe':
                return $this->redirectToStripe($paymentData);
            case 'paypal':
                return $this->redirectToPayPal($paymentData);
            case 'credit_card':
            default:
                return $this->redirectToLocalPaymentGateway($paymentData);
        }
    }

    /**
     * Redirect to Stripe payment
     */
    private function redirectToStripe($paymentData)
    {
        // For demo purposes, redirect to a mock Stripe payment page
        $stripeUrl = "https://checkout.stripe.com/pay?" . http_build_query([
            'amount' => $paymentData['amount'] * 100, // Stripe uses cents
            'currency' => strtolower($paymentData['currency']),
            'description' => $paymentData['description'],
            'email' => $paymentData['customer_email'],
            'success_url' => route('enrollment.payment.success', ['membership' => $paymentData['membership_id']]),
            'cancel_url' => route('enrollment.payment.cancel', ['membership' => $paymentData['membership_id']]),
        ]);

        return redirect($stripeUrl);
    }

    /**
     * Redirect to PayPal payment
     */
    private function redirectToPayPal($paymentData)
    {
        // For demo purposes, redirect to a mock PayPal payment page
        $paypalUrl = "https://www.paypal.com/cgi-bin/webscr?" . http_build_query([
            'cmd' => '_xclick',
            'business' => 'hermes.fitness@example.com',
            'item_name' => $paymentData['description'],
            'amount' => $paymentData['amount'],
            'currency_code' => $paymentData['currency'],
            'return' => route('enrollment.payment.success', ['membership' => $paymentData['membership_id']]),
            'cancel_return' => route('enrollment.payment.cancel', ['membership' => $paymentData['membership_id']]),
        ]);

        return redirect($paypalUrl);
    }

    /**
     * Redirect to local payment gateway (mock)
     */
    private function redirectToLocalPaymentGateway($paymentData)
    {
        // For demo purposes, redirect to a mock payment gateway
        return redirect()->route('payment.gateway', $paymentData);
    }

    /**
     * Handle successful payment
     */
    public function paymentSuccess(Request $request, $membershipId)
    {
        $membership = Membership::findOrFail($membershipId);
        
        // Activate membership and user account
        $membership->update(['status' => 'active']);
        $membership->user->update(['is_active' => true]);

        return view('frontend.payment-success', compact('membership'));
    }

    /**
     * Handle cancelled payment
     */
    public function paymentCancel(Request $request, $membershipId)
    {
        $membership = Membership::findOrFail($membershipId);
        
        // Mark membership as cancelled and delete user account
        $membership->update(['status' => 'cancelled']);
        $membership->user->delete();

        return view('frontend.payment-cancel');
    }
}
