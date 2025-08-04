<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Join Hermes Fitness - Register</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
            position: relative;
            margin: 0;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(40,167,69,0.05) 0%, rgba(32,201,151,0.05) 100%);
            z-index: 0;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
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
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><polygon points="0,100 100,0 100,100" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            position: relative;
            z-index: 2;
        }

        .gym-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #28a745;
            padding: 8px;
            background: #f8f9fa;
            margin-bottom: 1rem;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
        }

        .gym-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #212529;
            letter-spacing: -0.5px;
        }

        .gym-subtitle {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }

        .register-body {
            padding: 2.5rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 0.95rem;
        }

        .form-floating {
            margin-bottom: 1.2rem;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            border: 2px solid #e8ecef;
            border-radius: 12px;
            padding: 1rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(248, 249, 250, 0.8);
            height: 55px;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.1);
            background: white;
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .row-compact .col-md-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .auth-links {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e8ecef;
        }

        .auth-links a {
            color: #28a745;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-links a:hover {
            color: #20c997;
            text-decoration: none;
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

            .gym-title {
                font-size: 1.4rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .row-compact .col-md-6 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="register-container">
            <!-- Header with Logo -->
            <div class="register-header">
                <div class="logo-container">
                    <img src="{{ asset('frontend/images/gymlogo.png') }}" alt="Hermes Fitness Logo" class="gym-logo">
                    <h1 class="gym-title">JOIN HERMES FITNESS</h1>
                    <p class="gym-subtitle">Start Your Fitness Journey Today</p>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="register-body">
                <div class="welcome-text">
                    <h2 class="welcome-title">Create Your Account</h2>
                    <p class="welcome-subtitle">Join thousands of members achieving their fitness goals</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row row-compact">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                                       placeholder="Full Name">
                                <label for="name"><i class="fas fa-user me-2"></i>Full Name</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="username"
                                       placeholder="name@example.com">
                                <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row row-compact">
                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                       name="phone" value="{{ old('phone') }}"
                                       placeholder="Phone Number">
                                <label for="phone"><i class="fas fa-phone me-2"></i>Phone Number</label>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender -->
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

                    <!-- Date of Birth -->
                    <div class="form-floating">
                        <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                               name="date_of_birth" value="{{ old('date_of_birth') }}">
                        <label for="date_of_birth"><i class="fas fa-calendar me-2"></i>Date of Birth</label>
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="form-floating">
                        <textarea id="address" class="form-control @error('address') is-invalid @enderror"
                                  name="address" style="height: 80px;" placeholder="Enter your address">{{ old('address') }}</textarea>
                        <label for="address"><i class="fas fa-map-marker-alt me-2"></i>Address</label>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row row-compact">
                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="new-password"
                                       placeholder="Password">
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input id="password_confirmation" type="password" class="form-control"
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm Password">
                                <label for="password_confirmation"><i class="fas fa-lock me-2"></i>Confirm Password</label>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            <i class="fas fa-shield-alt me-1"></i>I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and <a href="#" class="text-decoration-none">Privacy Policy</a>
                        </label>
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
