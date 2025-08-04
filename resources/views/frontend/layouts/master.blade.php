<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Home</title>
  <!-- Bootstrap 5 CSS CDN -->
  <link href="{{asset('frontend/css/bootstrap.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="{{asset('frontend/css/trainer.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/about.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/pricing.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/services.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/contact.css')}}" rel="stylesheet">
  <link href="{{asset('frontend/css/payment.css')}}" rel="stylesheet">

   <!-- Custom Styling -->
   <style>
     .navbar-nav .nav-link.btn {
       border-radius: 20px !important;
       margin-left: 10px;
       transition: all 0.3s ease;
     }

     .navbar-nav .nav-link.btn:hover {
       transform: translateY(-2px);
       box-shadow: 0 4px 8px rgba(0,0,0,0.2);
     }

     .dropdown-menu {
       border-radius: 10px;
       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
       border: none;
     }

     .dropdown-item {
       transition: all 0.3s ease;
     }

     .dropdown-item:hover {
       background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
       color: white;
     }
   </style>
</head>
<body>

  <!-- Top Navigation Bar -->
 <nav class="navbar navbar-expand-sm navbar-dark bg-dark ">
    <div class="container">
      <!-- Gym Logo and Brand -->
      <a class="navbar-brand" href="#">
        <img src="{{asset('frontend/images/gymlogo.png')}}" alt="Gym Logo" />
        HERMES FITNESS
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#trainer">Trainers</a></li>
          <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
          <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        </ul>

        <!-- Authentication Links -->
        <ul class="navbar-nav">
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                  <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a></li>
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                  <i class="fas fa-user-edit me-2"></i>Profile
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                      <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt me-1"></i>Login
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-primary text-white ms-2 px-3" href="{{ route('register') }}">
                <i class="fas fa-user-plus me-1"></i>Join Now
              </a>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>


  <div>
    @yield('content')
  </div>


    <!-- Footer Section -->


  <footer class="footer bg-dark text-white pt-5 pb-4">
  <div class="container text-center text-md-left">
    <div class="row text-center text-md-left">

      <!-- Contact Info -->
      <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Hermes Fitness</h5>
        <p>Your fitness journey starts here. Get in touch with us to learn more about memberships, schedules, or any questions you have!</p>
      </div>

      <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Quick Links</h5>
        <p><a href="#home" class="text-white text-decoration-none">Home</a></p>
        <p><a href="#services" class="text-white text-decoration-none">Services</a></p>
        <p><a href="#pricing" class="text-white text-decoration-none">Pricing</a></p>
        <p><a href="#trainer" class="text-white text-decoration-none">Trainers</a></p>
      </div>


      <!-- Social Links -->
      <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
        <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Follow Us</h5>
        <a href="#" class="text-white me-4"><i class="fab fa-facebook fa-lg"></i></a>
        <a href="#" class="text-white me-4"><i class="fab fa-instagram fa-lg"></i></a>
        <a href="#" class="text-white me-4"><i class="fab fa-twitter fa-lg"></i></a>
        <a href="#" class="text-white"><i class="fab fa-youtube fa-lg"></i></a>
      </div>
    </div>

    <!-- Copyright -->
    <div class="row mt-3">
      <div class="col-md-12 text-center">
        <p class="mb-0">&copy; 2025 Hermes Fitness. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>


  <!-- Bootstrap 5 JS Bundle -->
    <script src="{{asset('frontend/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('frontend/js/payment.js')}}"></script>
    <script src="{{asset('frontend/js/trainer.js')}}"></script>
</body>
</html>
