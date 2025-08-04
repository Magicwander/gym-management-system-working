<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment Gateway - Hermes Fitness</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1e1e 0%, #2d2d2d 100%);
            min-height: 100vh;
            color: #fff;
        }
        
        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .payment-card {
            background: rgba(45, 45, 45, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        
        .payment-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            padding: 2rem;
            text-align: center;
        }
        
        .payment-header h1 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .payment-body {
            padding: 2rem;
        }
        
        .order-summary {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .form-label {
            color: #f8f9fa;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            color: #fff;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }
        
        .btn-secondary {
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        
        .payment-method-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .payment-method-card:hover {
            border-color: #28a745;
        }
        
        .payment-method-card.selected {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.1);
        }
        
        .security-info {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        
        .processing {
            display: none;
        }
        
        .spinner-border {
            width: 1.5rem;
            height: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-container">
            <div class="payment-card">
                <div class="payment-header">
                    <h1><i class="fas fa-lock me-3"></i>Secure Payment</h1>
                    <p class="mb-0">Complete your membership purchase</p>
                </div>
                
                <div class="payment-body">
                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h5 class="text-success mb-3"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
                        <div class="row">
                            <div class="col-6">
                                <p><strong>Member:</strong></p>
                                <p><strong>Membership:</strong></p>
                                <p><strong>Duration:</strong></p>
                                <p><strong>Amount:</strong></p>
                            </div>
                            <div class="col-6 text-end">
                                <p>{{ $membership->user->name }}</p>
                                <p>{{ ucfirst($membership->type) }}</p>
                                <p>{{ str_replace('_', ' ', ucfirst($membership->duration)) }}</p>
                                <p class="text-success"><strong>Rs {{ number_format($paymentData['amount'], 2) }}</strong></p>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="membership_id" value="{{ $paymentData['membership_id'] }}">
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <h5 class="text-success mb-3"><i class="fas fa-credit-card me-2"></i>Payment Method</h5>
                            
                            <div class="payment-method-card" data-method="credit_card">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="fas fa-credit-card me-2"></i>Credit Card
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-method-card" data-method="debit_card">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="debit_card" value="debit_card">
                                    <label class="form-check-label" for="debit_card">
                                        <i class="fas fa-credit-card me-2"></i>Debit Card
                                    </label>
                                </div>
                            </div>
                            
                            <div class="payment-method-card" data-method="bank_transfer">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="bank_transfer" value="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university me-2"></i>Bank Transfer
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Card Payment Fields -->
                        <div id="card_fields" class="payment-fields">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="cardholder_name" class="form-label">Cardholder Name *</label>
                                    <input type="text" class="form-control @error('cardholder_name') is-invalid @enderror" 
                                           id="cardholder_name" name="cardholder_name" value="{{ old('cardholder_name', $membership->user->name) }}">
                                    @error('cardholder_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="card_number" class="form-label">Card Number *</label>
                                    <input type="text" class="form-control @error('card_number') is-invalid @enderror" 
                                           id="card_number" name="card_number" placeholder="1234 5678 9012 3456" 
                                           maxlength="19" value="{{ old('card_number') }}">
                                    @error('card_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="expiry_month" class="form-label">Expiry Month *</label>
                                    <select class="form-select @error('expiry_month') is-invalid @enderror" 
                                            id="expiry_month" name="expiry_month">
                                        <option value="">Month</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ sprintf('%02d', $i) }}" {{ old('expiry_month') == sprintf('%02d', $i) ? 'selected' : '' }}>
                                                {{ sprintf('%02d', $i) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('expiry_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="expiry_year" class="form-label">Expiry Year *</label>
                                    <select class="form-select @error('expiry_year') is-invalid @enderror" 
                                            id="expiry_year" name="expiry_year">
                                        <option value="">Year</option>
                                        @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                            <option value="{{ $i }}" {{ old('expiry_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('expiry_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cvv" class="form-label">CVV *</label>
                                    <input type="password" class="form-control @error('cvv') is-invalid @enderror" 
                                           id="cvv" name="cvv" placeholder="123" maxlength="3" value="{{ old('cvv') }}">
                                    @error('cvv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bank_fields" class="payment-fields" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="bank_name" class="form-label">Bank Name *</label>
                                    <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                           id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                    @error('bank_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="account_number" class="form-label">Account Number *</label>
                                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                           id="account_number" name="account_number" value="{{ old('account_number') }}">
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="security-info">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <small>Your payment information is encrypted and secure. We never store your card details.</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-success btn-lg me-3" id="payButton">
                                    <span class="button-text">
                                        <i class="fas fa-lock me-2"></i>Pay Rs {{ number_format($paymentData['amount'], 2) }}
                                    </span>
                                    <span class="processing">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                        Processing...
                                    </span>
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Payment method selection
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Check the radio button
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Show/hide payment fields
                togglePaymentFields(radio.value);
            });
        });

        function togglePaymentFields(method) {
            const cardFields = document.getElementById('card_fields');
            const bankFields = document.getElementById('bank_fields');
            
            if (method === 'bank_transfer') {
                cardFields.style.display = 'none';
                bankFields.style.display = 'block';
            } else {
                cardFields.style.display = 'block';
                bankFields.style.display = 'none';
            }
        }

        // Card number formatting
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });

        // Form submission with loading state
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const button = document.getElementById('payButton');
            const buttonText = button.querySelector('.button-text');
            const processing = button.querySelector('.processing');
            
            button.disabled = true;
            buttonText.style.display = 'none';
            processing.style.display = 'inline';
        });

        // Initialize payment method selection
        document.querySelector('input[name="payment_method"]:checked').closest('.payment-method-card').classList.add('selected');
    </script>
</body>
</html>
