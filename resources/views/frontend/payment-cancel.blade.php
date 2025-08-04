<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Cancelled - Hermes Fitness</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .cancel-container {
            max-width: 600px;
            text-align: center;
            padding: 2rem;
        }
        
        .cancel-card {
            background: rgba(45, 45, 45, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 2rem;
        }
        
        .cancel-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: shake 0.5s ease-in-out;
        }
        
        .cancel-icon i {
            font-size: 3rem;
            color: white;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .cancel-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        
        .cancel-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }
        
        .info-box {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .info-box h5 {
            color: #dc3545;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .info-box ul {
            list-style: none;
            padding: 0;
        }
        
        .info-box li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .info-box li:last-child {
            border-bottom: none;
        }
        
        .info-box i {
            color: #dc3545;
            margin-right: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 0.5rem;
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
            margin: 0.5rem;
        }
        
        .btn-secondary:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            color: #fff;
        }
        
        .help-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .help-section h6 {
            color: #ff6b6b;
            margin-bottom: 1rem;
        }
        
        .contact-info {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .contact-item {
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
        
        .contact-item i {
            font-size: 1.5rem;
            color: #ff6b6b;
            margin-bottom: 0.5rem;
        }
        
        .contact-item small {
            display: block;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cancel-container">
            <div class="cancel-card">
                <div class="cancel-icon">
                    <i class="fas fa-times"></i>
                </div>
                
                <h1 class="cancel-title">Payment Cancelled</h1>
                <p class="cancel-subtitle">Your payment was cancelled and no charges were made to your account.</p>
                
                <div class="info-box">
                    <h5><i class="fas fa-info-circle me-2"></i>What Happened?</h5>
                    <ul>
                        <li><i class="fas fa-circle-xmark"></i>Your enrollment and payment process was cancelled</li>
                        <li><i class="fas fa-circle-xmark"></i>No membership account was created</li>
                        <li><i class="fas fa-circle-xmark"></i>No charges were made to your payment method</li>
                        <li><i class="fas fa-circle-check"></i>You can try enrolling again at any time</li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('enrollment.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-redo me-2"></i>Try Again
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
                
                <div class="help-section">
                    <h6><i class="fas fa-question-circle me-2"></i>Need Help?</h6>
                    <p class="text-muted mb-3">If you're experiencing issues with payment or have questions about our membership plans, we're here to help!</p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>Email Support</div>
                            <small>support@hermesfitness.com</small>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>Phone Support</div>
                            <small>+92-XXX-XXXXXXX</small>
                        </div>
                        
                        <div class="contact-item">
                            <i class="fas fa-comments"></i>
                            <div>Live Chat</div>
                            <small>Available 24/7</small>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>Your privacy and security are our top priority. No personal information was stored during this cancelled transaction.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-redirect to home after 60 seconds
        let countdown = 60;
        const redirectTimer = setInterval(function() {
            countdown--;
            if (countdown <= 0) {
                clearInterval(redirectTimer);
                window.location.href = '{{ route("home") }}';
            }
        }, 1000);
        
        // Show countdown in console for debugging
        console.log('Auto-redirect to home page in 60 seconds...');
    </script>
</body>
</html>
