<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful - Hermes Fitness</title>

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
        
        .success-container {
            max-width: 600px;
            text-align: center;
            padding: 2rem;
        }
        
        .success-card {
            background: rgba(45, 45, 45, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 3rem 2rem;
        }
        
        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s infinite;
        }
        
        .success-icon i {
            font-size: 3rem;
            color: white;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 1rem;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }
        
        .membership-details {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.3);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .membership-details h5 {
            color: #28a745;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .detail-row:last-child {
            border-bottom: none;
            font-weight: 600;
            color: #28a745;
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
        
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        }
        
        .next-steps {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 1.5rem;
            margin-top: 2rem;
            text-align: left;
        }
        
        .next-steps h6 {
            color: #ff6b6b;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .next-steps ul {
            list-style: none;
            padding: 0;
        }
        
        .next-steps li {
            padding: 0.5rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .next-steps li:last-child {
            border-bottom: none;
        }
        
        .next-steps i {
            color: #28a745;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-card">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                
                <h1 class="success-title">Payment Successful!</h1>
                <p class="success-subtitle">Welcome to Hermes Fitness! Your membership is now active.</p>
                
                <div class="membership-details">
                    <h5><i class="fas fa-receipt me-2"></i>Membership Details</h5>
                    
                    <div class="detail-row">
                        <span>Member Name:</span>
                        <span>{{ $membership->user->name }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Email:</span>
                        <span>{{ $membership->user->email }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Membership Type:</span>
                        <span>{{ ucfirst($membership->type) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Duration:</span>
                        <span>{{ str_replace('_', ' ', ucfirst($membership->duration)) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Start Date:</span>
                        <span>{{ $membership->start_date->format('F d, Y') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>End Date:</span>
                        <span>{{ $membership->end_date->format('F d, Y') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span>Amount Paid:</span>
                        <span>Rs {{ number_format($membership->price, 2) }}</span>
                    </div>
                </div>
                
                <div class="next-steps">
                    <h6><i class="fas fa-list-check me-2"></i>What's Next?</h6>
                    <ul>
                        <li><i class="fas fa-envelope"></i>Check your email for membership confirmation and login details</li>
                        <li><i class="fas fa-key"></i>Your temporary password is: <strong>password123</strong> (please change it after login)</li>
                        <li><i class="fas fa-calendar"></i>Visit our gym and start your fitness journey</li>
                        <li><i class="fas fa-user"></i>Complete your profile and set your fitness goals</li>
                        <li><i class="fas fa-dumbbell"></i>Book your first workout session with our trainers</li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-home me-2"></i>Back to Home
                    </a>
                </div>
                
                <div class="mt-4">
                    <small class="text-muted">
                        <i class="fas fa-phone me-1"></i>Need help? Contact us at support@hermesfitness.com or call +92-XXX-XXXXXXX
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-redirect to login after 30 seconds
        setTimeout(function() {
            if (confirm('Would you like to login to your account now?')) {
                window.location.href = '{{ route("login") }}';
            }
        }, 30000);
        
        // Confetti animation (optional)
        function createConfetti() {
            const colors = ['#ff6b6b', '#28a745', '#20c997', '#ffd93d', '#6c5ce7'];
            
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.style.position = 'fixed';
                confetti.style.width = '10px';
                confetti.style.height = '10px';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.top = '-10px';
                confetti.style.zIndex = '9999';
                confetti.style.borderRadius = '50%';
                confetti.style.pointerEvents = 'none';
                
                document.body.appendChild(confetti);
                
                const animation = confetti.animate([
                    { transform: 'translateY(0) rotate(0deg)', opacity: 1 },
                    { transform: 'translateY(100vh) rotate(360deg)', opacity: 0 }
                ], {
                    duration: Math.random() * 3000 + 2000,
                    easing: 'linear'
                });
                
                animation.onfinish = () => confetti.remove();
            }
        }
        
        // Trigger confetti on page load
        window.addEventListener('load', createConfetti);
    </script>
</body>
</html>
