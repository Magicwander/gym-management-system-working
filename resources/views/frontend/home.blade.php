@extends('frontend.layouts.master')

@section('content')
<!-- Hero Section -->
<section id="home">
  <div class="hero">
    <div>
      <h1>Your<strong style="color: gray;"> Best Body</strong></h1>
      <p><strong>Getting in shape isn't hard when you're in a supportive environment.</strong></p>
      <p><strong>Hermes Fitness is more than just a gym - it's a caring family that's there to help</strong></p>
      <p><strong>you achieve your goals</strong></p>
      @auth
        <div class="mt-4">
          <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg me-3" style="padding: 12px 30px; font-weight: 600; border-radius: 25px;">
            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
          </a>
          <span class="text-white">Welcome back, {{ auth()->user()->name }}!</span>
        </div>
      @else
        <div class="mt-4">
          <a href="{{ route('register') }}" class="btn btn-warning btn-lg me-3" style="padding: 12px 30px; font-weight: 600; border-radius: 25px; color: #333;">
            <i class="fas fa-user-plus me-2"></i>Join Now
          </a>
          <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg" style="padding: 12px 30px; font-weight: 600; border-radius: 25px; border-width: 2px;">
            <i class="fas fa-sign-in-alt me-2"></i>Member Login
          </a>
        </div>
      @endauth
    </div>
  </div>
</section>

<section id="about">
<div class="text-center">
    <h1 class="page-title">About Us</h1>
  </div>

  <div class="about-container container">
    <div class="about-row row">

      <!-- Image Carousel on Left -->
      <div class="col-md-6 d-flex justify-content-center">
        <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
          <div class="carousel-inner rounded">

            <div class="carousel-item active">
              <img src="{{asset('frontend/images/GPJNews_SriLanka_VT_FitnessCenter_069_web.jpg')}}" alt="Slide 1" />
            </div>
            <div class="carousel-item">
              <img src="{{asset('frontend/images/images.jpg')}}" alt="Slide 2" />
            </div>
            <div class="carousel-item">
              <img src="{{asset('frontend/images/strength.jpg')}}" alt="Slide 3" />
            </div>

          </div>

          <!-- Carousel controls (arrows) -->
          <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>

     <!-- Carousel on the Right -->
      <div class="col-md-6 carousel-section">
        <div id="textCarousel" class="carousel slide" data-bs-ride="carousel">

          <!-- Indicators -->
          <div class="carousel-indicators mb-3">
            <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#textCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
          </div>

          <!-- Carousel Text Slides -->
          <div class="carousel-inner" style="color: white;">
            <div class="carousel-item active">
              <h5>Join our Gym Family</h5>
              <p>There is more to Hermes Fitness than just a gym. Our family is here to support you in achieving your objectives. Having fun along the road is the best approach to achieve your goals, and it's always more enjoyable when you're around others that care!</p>
            </div>
            <div class="carousel-item">
              <h5>Personalized Training</h5>
              <p>A complimentary customized diet and training plan tailored to each member's lifestyle, food preferences, and fitness objectives is provided by Hermes Fitness. We support each person according to their objectives and way of life.</p>
            </div>
            <div class="carousel-item">
              <h5>Caring Environment</h5>
              <p>Doing something that you won't enjoy is pointless. In the end, what will keep you consistent is whether or not you truly appreciate the atmosphere you're in. An affectionate second home is what Hermes Fitness is!</p>
            </div>
          </div>

        </div>
      </div>

          <!-- Optional controls for text carousel (hidden here but you can enable if you want) -->
          <!--
          <button class="carousel-control-prev" type="button" data-bs-target="#textCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#textCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
          -->

        </div>
      </div>

    </div>
  </div>

</section>

<section id="services">
  <section class="services-section">
    <div class="services-title">Services</div><br><br><br>

    <div class="services-content">
      <div class="services-left"><br>

        <div class="service-box">
          <img src="{{asset('frontend/images/muscle.png')}}" alt="Training Icon" />
          <div>
            <h3>One To One Training</h3>
            <p>Every one of our professional personal trainers is prepared to assist you with any fitness objective you may have!</p>
          </div>
        </div>

        <div class="service-box">
          <img src="{{asset('frontend/images/scale.png')}}" alt="Fitness Icon" />
          <div>
            <h3>Fitness Check-Up</h3>
            <p>To monitor your body composition, get your body mass index and fat percentage measured each month.</p>
          </div>
        </div>

        <div class="service-box">
          <img src="{{asset('frontend/images/apple.png')}}" alt="Juice Bar Icon" />
          <div>
            <h3>Juice & supplement Bar</h3>
            <p>At the gym, we have a supplement bar that includes Greek yogurt, BCAA, whey protein, pre-workout, and more!</p>
          </div>
        </div>

      </div>

      <div class="services-right">
        <video controls>
          <source src="{{asset('frontend/videos/Fitness Cinematic video _ Gym commercial _ Cinematic fitness film _ Fitness commercial.mp4')}}" type="video/mp4" />
        </video>
      </div>
    </div>
  </section>
</section>

<section id="trainer">
   <div class="container">
    <div class="trainer-title">Meet Our Team</div>

    <div class="row justify-content-center" style="margin-left: 40px;">

      <!-- Using the custom element -->
      <div class="col-md-4 mb-4">
      <trainer-card
        name="Coach Madhava"
        img="{{asset('frontend/images/Madhava.jpg')}}"
        experience="10 Years Experience - Certified Strength Coach"
        description="Coach Madhava has been training clients for over a decade, focusing on strength and endurance. He holds a Certified Strength Coach credential and specializes in personalized resistance training.">
      </trainer-card>
      </div>

      <div class="col-md-4 mb-4">
      <trainer-card
        name="Coach Yohan"
        img="{{asset('frontend/images/yohan.jpg')}}"
        experience="8 Years Experience - ISSA Certified Trainer And Coach"
        description="Coach Yohan is an ISSA Certified Personal Trainer with 8 years of experience. He excels in functional fitness, group training, and bodyweight-based workouts for all fitness levels.">
      </trainer-card>
      </div>

      <div class="col-md-4 mb-4">
      <trainer-card
        name="Coach Arun"
        img="{{asset('frontend/images/Arun.jpg')}}"
        experience="6 Years Experience - ACE Certified Instructor"
        description="Coach Arun is an ACE Certified Instructor with a background in sports science. He brings 6 years of experience in strength training, athletic conditioning, and fitness coaching.">
      </trainer-card>
      </div>

    </div>

    <p class="bottom-paragraph">🏋️All of our teachers are certified professionals who have a strong interest in fitness.</p>
  </div>

</section>

<section id="pricing" class="py-5">
  <div class="container">
    <h2 class="pricing-title">Membership Plans</h2>

    <div class="row justify-content-center">
      <!-- Platinum Membership -->
      <div class="col-md-4 mb-4">
        <div class="card pricing-card h-100">
          <!--<div class="card-header bg-dark text-white text-center">
          </div>-->
          <div class="card-body text-center">
            <img src="{{asset('frontend/images/Adobe Express - file (1).png')}}" class="img-fluid mb-3" alt="Platinum Plan">
            <h3><strong style="color:#ff6464;">Platinum</strong><strong> Membership</strong></h3><br><br>
            <h5>Gents(Annual) - Rs 65000</h5>
            <h5>Ladies(Annual) - Rs 55000</h5>
            <h5>Couple(Annual) - Rs 85000</h5>
            <br><br><br>
            <h5>Time Duration:</h5>
            <h6 style="color: rgb(255, 48, 48);">5:00am to 12:00 Midnight</h6>
          </div>
          <div class="card-footer text-center">
            <a href="{{ route('register.customer') }}" class="btn btn-success">Join Now</a>
          </div>
        </div>
      </div>

      <!-- Gold Membership-->
      <div class="col-md-4 mb-4">
        <div class="card pricing-card h-100">
          <!--<div class="card-header bg-warning text-white text-center">
          </div>-->
          <div class="card-body text-center">
            <img src="{{asset('frontend/images/Adobe Express - file (2).png')}}" class="img-fluid mb-3" alt="Gold Plan">
            <h3><strong style="color:#ff6464;">Gold</strong><strong> Membership</strong></h3><br><br>
            <h5>Gents(Annual) - Rs 55000</h5>
            <h5>Ladies(Annual) - Rs 45000</h5><br><br><br><br><br>
            <h5>Time Duration:</h5>
            <h6 style="color: rgb(255, 48, 48);">5:00am to 4:30pm</h6>
          </div>
          <div class="card-footer text-center">
            <a href="{{ route('register.customer') }}" class="btn btn-success">Join Now</a>
          </div>
        </div>
      </div>

      <!-- Silver Membership -->
      <div class="col-md-4 mb-4">
        <div class="card pricing-card h-100">
          <!--<div class="card-header bg-secondary text-white text-center"> </div>-->
          <div class="card-body text-center">
            <img src="{{asset('frontend/images/Adobe Express - file (3).png')}}" class="img-fluid mb-3" alt="Silver Plan">
            <h3><strong style="color:#ff6464;">Silver</strong><strong> Membership</strong></h3><br><br>
            <h5>Individual(6-months) - Rs 45000</h5>
            <h5>Individual(3-months) - Rs 35000</h5><br><br><br><br><br>
            <h5>Time Duration:</h5>
            <h6 style="color: rgb(255, 48, 48);">5:00am to 12 Midnight</h6>
          </div>
          <div class="card-footer text-center">
            <a href="{{ route('register.customer') }}" class="btn btn-success">Join Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>

 <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <form id="bookingForm">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title" id="bookingModalLabel">Membership Booking</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

          <!-- Full Name -->
          <div class="mb-3">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" required />
          </div>

          <!-- Email -->
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" required />
          </div>

          <!-- Phone -->
          <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" required />
          </div>

          <!-- Age -->
          <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" required min="12" max="100" />
          </div>

          <!-- Gender -->
          <div class="mb-3">
            <label class="form-label">Gender</label><br />
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required />
              <label class="form-check-label" for="male">Male</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="gender" id="female" value="Female" />
              <label class="form-check-label" for="female">Female</label>
            </div>
          </div>

          <!-- Address -->
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" rows="2" required></textarea>
          </div>

          <!-- Membership Type -->
          <div class="mb-3">
            <label for="membershipType" class="form-label">Membership Type</label>
            <input type="text" class="form-control" id="membershipType" readonly />
          </div>

          <!-- Package Options -->
          <div class="mb-3">
            <label for="membershipPackage" class="form-label">Select Package</label>
            <select class="form-select" id="membershipPackage" required>
              <option value="">Select a package</option>
            </select>
          </div>

          <!-- Payment Method -->
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="Credit Card" checked />
              <label class="form-check-label" for="creditCard">Credit Card</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="PayPal" />
              <label class="form-check-label" for="paypal">PayPal</label>
            </div>
          </div>

          <!-- Credit Card Info -->
          <div id="creditCardInfo">
            <div class="mb-3">
              <label for="cardNumber" class="form-label">Card Number</label>
              <input type="text" class="form-control" id="cardNumber" placeholder="XXXX-XXXX-XXXX-XXXX" required />
            </div>
            <div class="mb-3">
              <label for="expiryDate" class="form-label">Expiry Date</label>
              <input type="month" class="form-control" id="expiryDate" required />
            </div>
            <div class="mb-3">
              <label for="cvv" class="form-label">CVV</label>
              <input type="password" class="form-control" id="cvv" maxlength="4" required />
            </div>
          </div>

          <!-- Terms -->
          <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="terms" required />
            <label class="form-check-label" for="terms">I accept the terms and conditions</label>
          </div>

          <!-- Hidden field to store selected price -->
          <input type="hidden" id="selectedPrice" name="selectedPrice" />
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Confirm Booking</button>
        </div>
      </form>

    </div>
  </div>
</div>
</section>
<!-- Bootstrap JS and Popper -->

<!-- Booking Script -->



 <section id="contact" class="contact-section">
        <div class="container my-5">
            <h1 class="section-title">
                Contact Us
            </h1>

            <div class="row g-4 justify-content-center">
                <!-- Contact Form Card -->
                <div class="col-12 col-lg-6">
                    <div class="contact-card">
                        <div class="card-body">
                            <form class="contact-form">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name" class="form-control" aria-label="Your Name">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" aria-label="Your Email">
                                </div>
                                <div class="mb-4">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea id="message" name="message" rows="7" class="form-control" style="resize: vertical;" aria-label="Your Message"></textarea>
                                </div>
                                <button type="submit" class="btn btn-custom-dark w-auto">
                                    SEND
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Map Card -->
                <div class="col-12 col-lg-6">
                    <div class="contact-card p-0 overflow-hidden position-relative">
                        <div class="map-container w-100">
                            <!-- Google Maps Embed with random Katunayake location -->
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.00!2d79.88!3d7.18!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2ee4f8f4a13e5%3A0x3f5d5b7c7b2e9d2d!2sKatunayake!5e0!3m2!1sen!2slk!4v1700000000000!5m2!1sen!2slk"
                                width="100%"
                                height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Location Map - Katunayake"
                            ></iframe>
                        </div>
                        <!-- Contact Info Overlay -->
                        <div class="contact-info-overlay">
                            <div>
                                <span>📞</span>
                                <span>+94712369851</span>
                            </div>
                            <div>
                                <span>📧</span>
                                <span>info@hermes.club.lk</span>
                            </div>
                            <div>
                                <span>📍</span>
                                <span>505/D, Sudarshana Mw, Katunayaka</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection