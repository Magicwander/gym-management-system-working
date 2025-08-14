<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Hermes Fitness</title>

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
            position: relative;
            margin: 0;
            padding: 20px 0;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0,123,255,0.05) 0%, rgba(108,117,125,0.05) 100%);
            z-index: 0;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
            border: 1px solid #e9ecef;
            margin: auto;
        }

        .login-header {
            background: white;
            padding: 2.5rem 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid #f1f3f4;
            position: relative;
        }

        .logo-container {
            position: relative;
        }

        .gym-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 3px solid #007bff;
            padding: 8px;
            background: #f8f9fa;
            margin-bottom: 1.5rem;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        }

        .gym-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #212529;
            letter-spacing: -0.5px;
        }

        .gym-subtitle {
            font-size: 0.95rem;
            color: #6c757d;
            font-weight: 500;
        }

        .login-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.5rem;
            letter-spacing: -0.3px;
        }

        .welcome-subtitle {
            color: #6c757d;
            font-size: 1rem;
            font-weight: 400;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating > .form-control {
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 1rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            height: 58px;
        }

        .form-floating > .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.1);
            background: white;
        }

        .form-floating > label {
            color: #6c757d;
            font-weight: 500;
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input:checked {
            background-color: #007bff;
            border-color: #007bff;
        }

        .form-check-label {
            color: #495057;
            font-weight: 500;
        }

        .btn-login {
            background: #007bff;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 1rem;
            letter-spacing: 0.3px;
        }

        .btn-login:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
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
            transition: all 0.3s ease;
        }

        .auth-links a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e8ecef, transparent);
        }

        .divider span {
            background: white;
            padding: 0 1.5rem;
            color: #666;
            font-weight: 500;
        }

        .demo-credentials {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 1px solid #e9ecef;
            position: relative;
        }

        .demo-credentials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: #007bff;
            border-radius: 2px 0 0 2px;
        }

        .demo-credentials h6 {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .demo-credentials .credential-item {
            background: white;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border: 1px solid #e9ecef;
        }

        .demo-credentials small {
            color: #495057;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .login-container {
                margin: 1rem;
                max-width: 95%;
            }

            .login-header {
                padding: 1.5rem;
            }

            .login-body {
                padding: 1.5rem;
            }

            .gym-title {
                font-size: 1.5rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="login-container">
            <!-- Header with Logo -->
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('frontend/images/gymlogo.png') }}" alt="Hermes Fitness Logo" class="gym-logo">
                    <h1 class="gym-title">HERMES FITNESS</h1>
                    <p class="gym-subtitle">Transform Your Body, Transform Your Life</p>
                </div>
            </div>

            <!-- Login Form -->
            <div class="login-body">
                <div class="welcome-text">
                    <h2 class="welcome-title">Welcome Back!</h2>
                    <p class="welcome-subtitle">Sign in to continue your fitness journey</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.test') }}">
                    {{-- @csrf --}}

                    <!-- Email Address -->
                    <div class="form-floating">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               placeholder="name@example.com">
                        <label for="email"><i class="fas fa-envelope me-2"></i>Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-floating">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password"
                               placeholder="Password">
                        <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">
                            <i class="fas fa-heart me-1"></i>Remember me
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In to Your Account
                    </button>
                </form>

                <div class="auth-links">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            <i class="fas fa-key me-1"></i>Forgot your password?
                        </a>
                    @endif
                    <div class="mt-2">
                        <span class="text-muted">Don't have an account? </span>
                        <a href="{{ route('register.customer') }}">
                            <i class="fas fa-user-plus me-1"></i>Join as Member
                        </a>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="demo-credentials">
                    <h6><i class="fas fa-info-circle me-2"></i>Demo Accounts</h6>
                    <div class="credential-item">
                        <small><strong><i class="fas fa-crown me-1 text-warning"></i>Admin:</strong> admin@hermesfitness.com</small>
                    </div>
                    <div class="credential-item">
                        <small><strong><i class="fas fa-user-tie me-1 text-success"></i>Trainer:</strong> trainer@hermesfitness.com</small>
                    </div>
                    <div class="credential-item">
                        <small><strong><i class="fas fa-user me-1 text-primary"></i>Member:</strong> john@example.com</small>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted"><i class="fas fa-lock me-1"></i>Password: <strong>password</strong></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
