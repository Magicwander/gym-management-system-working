<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customer Registration - Hermes Fitness</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,123,255,0.05) 0%, rgba(108,117,125,0.05) 100%);
            z-index: 0;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            position: relative;
            z-index: 1;
            border: 1px solid #e9ecef;
            margin: auto;
        }

        .register-header {
            background: white;
            padding: 2rem 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid #f1f3f4;
        }

        .gym-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #007bff;
            padding: 8px;
            background: #f8f9fa;
            margin-bottom: 1rem;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .gym-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #212529;
        }

        .gym-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .register-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-floating {
            margin-bottom: 1.25rem;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 1rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fff;
            height: 58px;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-register {
            background: #007bff;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
            margin-top: 1rem;
        }

        .btn-register:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
            color: white;
        }

        .auth-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .auth-links a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .row .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 1rem;
                max-width: 95%;
            }

            .register-header {
                padding: 1.5rem;
            }

            .register-body {
                padding: 1.5rem;
            }

            .row .col-md-6 {
                padding-left: 0;
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="register-container">
            <!-- Header with Logo -->
            <div class="register-header">
                <img src="{{ asset('frontend/images/gymlogo.png') }}" alt="Hermes Fitness Logo" class="gym-logo">
                <h1 class="gym-title">HERMES FITNESS</h1>
                <p class="gym-subtitle">Transform Your Body, Transform Your Life</p>
            </div>

            <!-- Registration Form -->
            <div class="register-body">
                <div class="welcome-text">
                    <h2 class="welcome-title">Join Our Fitness Community!</h2>
                    <p class="welcome-subtitle">Create your account and start your fitness journey today</p>
                </div>

                <form method="POST" action="{{ route('register.customer.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-floating">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Full Name">
                        <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-floating">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required
                               placeholder="name@example.com">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-floating">
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                               name="phone" value="{{ old('phone') }}"
                               placeholder="Phone Number">
                        <label for="phone"><i class="fas fa-phone me-2"></i>Phone Number</label>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date of Birth and Gender -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                                       name="date_of_birth" value="{{ old('date_of_birth') }}">
                                <label for="date_of_birth"><i class="fas fa-calendar me-2"></i>Date of Birth</label>
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                <label for="gender"><i class="fas fa-venus-mars me-2"></i>Gender</label>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-floating">
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                  name="address" style="height: 80px;" placeholder="Address">{{ old('address') }}</textarea>
                        <label for="address"><i class="fas fa-map-marker-alt me-2"></i>Address</label>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="emergency_contact" type="text" class="form-control @error('emergency_contact') is-invalid @enderror"
                                       name="emergency_contact" value="{{ old('emergency_contact') }}"
                                       placeholder="Emergency Contact Name">
                                <label for="emergency_contact"><i class="fas fa-user-shield me-2"></i>Emergency Contact</label>
                                @error('emergency_contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="emergency_phone" type="tel" class="form-control @error('emergency_phone') is-invalid @enderror"
                                       name="emergency_phone" value="{{ old('emergency_phone') }}"
                                       placeholder="Emergency Phone">
                                <label for="emergency_phone"><i class="fas fa-phone-alt me-2"></i>Emergency Phone</label>
                                @error('emergency_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Medical Conditions -->
                    <div class="form-floating">
                        <textarea id="medical_conditions" class="form-control @error('medical_conditions') is-invalid @enderror"
                                  name="medical_conditions" style="height: 80px;" 
                                  placeholder="Any medical conditions or allergies">{{ old('medical_conditions') }}</textarea>
                        <label for="medical_conditions"><i class="fas fa-heartbeat me-2"></i>Medical Conditions</label>
                        @error('medical_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Fitness Goals -->
                    <div class="form-floating">
                        <textarea id="fitness_goals" class="form-control @error('fitness_goals') is-invalid @enderror"
                                  name="fitness_goals" style="height: 80px;" 
                                  placeholder="What are your fitness goals?">{{ old('fitness_goals') }}</textarea>
                        <label for="fitness_goals"><i class="fas fa-bullseye me-2"></i>Fitness Goals</label>
                        @error('fitness_goals')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required placeholder="Password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating">
                        <input id="password_confirmation" type="password" class="form-control"
                               name="password_confirmation" required placeholder="Confirm Password">
                        <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Create My Account
                    </button>
                </form>

                <div class="auth-links">
                    <span class="text-muted">Already have an account? </span>
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Sign in here
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>