<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enroll - Hermes Fitness</title>

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
        
        .enrollment-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
        }
        
        .enrollment-card {
            background: rgba(45, 45, 45, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        
        .enrollment-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            padding: 2rem;
            text-align: center;
        }
        
        .enrollment-header h1 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }
        
        .enrollment-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1.1rem;
        }
        
        .enrollment-body {
            padding: 2rem;
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
            border-color: #ff6b6b;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
            color: #fff;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
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
        
        .membership-card {
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .membership-card:hover {
            border-color: #ff6b6b;
            transform: translateY(-2px);
        }
        
        .membership-card.selected {
            border-color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
        }
        
        .membership-type {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ff6b6b;
            margin-bottom: 0.5rem;
        }
        
        .membership-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #fff;
        }
        
        .invalid-feedback {
            color: #ff6b6b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .alert-danger {
            background-color: rgba(255, 107, 107, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="enrollment-container">
            <div class="enrollment-card">
                <div class="enrollment-header">
                    <h1><i class="fas fa-dumbbell me-3"></i>Join Hermes Fitness</h1>
                    <p>Start your fitness journey today with our premium membership plans</p>
                </div>
                
                <div class="enrollment-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('enrollment.store') }}" method="POST" id="enrollmentForm">
                        @csrf
                        
                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Personal Information</h4>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="age" class="form-label">Age *</label>
                                <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                       id="age" name="age" value="{{ old('age') }}" min="12" max="100" required>
                                @error('age')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender *</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Membership Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h4 class="text-primary mb-3"><i class="fas fa-crown me-2"></i>Choose Your Membership</h4>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="membership-card" data-type="silver">
                                    <div class="membership-type">Silver</div>
                                    <div class="membership-price">Rs 2,000/month</div>
                                    <small class="text-muted">Basic gym access</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="membership-card" data-type="gold">
                                    <div class="membership-type">Gold</div>
                                    <div class="membership-price">Rs 3,500/month</div>
                                    <small class="text-muted">Gym + Group classes</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="membership-card" data-type="platinum">
                                    <div class="membership-type">Platinum</div>
                                    <div class="membership-price">Rs 5,000/month</div>
                                    <small class="text-muted">All access + Personal trainer</small>
                                </div>
                            </div>
                            
                            <input type="hidden" name="membership_type" id="membership_type" value="{{ old('membership_type') }}">
                            @error('membership_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration Selection -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="membership_duration" class="form-label">Membership Duration *</label>
                                <select class="form-select @error('membership_duration') is-invalid @enderror" 
                                        id="membership_duration" name="membership_duration" required>
                                    <option value="">Select Duration</option>
                                    <option value="3_months" {{ old('membership_duration') == '3_months' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6_months" {{ old('membership_duration') == '6_months' ? 'selected' : '' }}>6 Months (5% discount)</option>
                                    <option value="1_year" {{ old('membership_duration') == '1_year' ? 'selected' : '' }}>1 Year (10% discount)</option>
                                </select>
                                @error('membership_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Payment Method *</label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="credit_card" value="credit_card" {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="credit_card">
                                                <i class="fas fa-credit-card me-2"></i>Credit Card
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="paypal" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="paypal">
                                                <i class="fab fa-paypal me-2"></i>PayPal
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="stripe" value="stripe" {{ old('payment_method') == 'stripe' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stripe">
                                                <i class="fab fa-stripe me-2"></i>Stripe
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary btn-lg me-3">
                                    <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Home
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
        // Membership type selection
        document.querySelectorAll('.membership-card').forEach(card => {
            card.addEventListener('click', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.membership-card').forEach(c => c.classList.remove('selected'));
                
                // Add selected class to clicked card
                this.classList.add('selected');
                
                // Set hidden input value
                document.getElementById('membership_type').value = this.dataset.type;
            });
        });

        // Set initial selection if old value exists
        const oldMembershipType = '{{ old("membership_type") }}';
        if (oldMembershipType) {
            const card = document.querySelector(`[data-type="${oldMembershipType}"]`);
            if (card) {
                card.classList.add('selected');
            }
        }
    </script>
</body>
</html>
