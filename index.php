<?php include_once __DIR__ . '/api/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Bountiful Bentos Home</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Concert+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/home.css">
    <script defer src="js/carousel.js"></script>
  </head>

  <body>
    <!-- ===== NAVBAR ===== -->
     <header class="navbar">
      <div class="nav-container">
        <a href="index.php"><img src="assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo"></a>

        <nav class="nav-links">
          <a href="src/menu.php">Menu</a>
          <a href="src/locate_us.html">Locate Us</a>
        </nav>

        <div class="nav-icons">
          <a href="src/cart.html"><img src="assets/images/Cart_BB.png" alt="Cart" class="icon"></a>
          <a href="src/login.html"><img src="assets/images/User_BB.png" alt="Login" class="icon"></a>
        </div>
      </div>
     </header>

    <!-- ===== HERO SECTION, MAIN PIC ===== -->
    <section class="hero">
      <img src="assets/images/Home_MainImg.jpg" alt="Bento Background" class="hero-img">
      <div class="hero-text">
        <h1>Bountiful Bentos</h1>
        <p>Nutritious Bentos to Fuel Your Busy Days</p>
        <button class="order-btn">Order Now</button>
      </div>
    </section>

    <!-- ===== PRODUCT CAROUSELS ===== -->
    <section class="carousel-section">
      <h2>Offers</h2>
      <div class="carousel" id="offers-carousel"></div>

      <h2>New</h2>
      <div class="carousel" id="new-carousel"></div>
    </section>

    <!-- ===== REVIEWS ===== -->
    <section class="reviews">
      <h2>What Our Customers Say</h2>

      <div class="review-container">
        <div class="review">
          <q>Quick, affordable, and delicious. Love the variety!</q><br>
          <strong>- Priya, NTU Year 3</strong>
        </div>

        <div class="review">
          <q>The bentos are super tasty and healthy! Perfect for my lunch breaks!</q><br>
          <strong>- Alex, NTU Year 2</strong>
        </div>
      </div>

      <div style="clear: both;"></div>
    </section>


    <!-- ===== FOOTER ===== -->
    <footer>
      <div class="footer-container">
        <div class="footer-column">
          <img src="assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo">
        </div>

        <div class="footer-column">
          <h3>About Us</h3>
          <a href="our_story.html">Our Story</a><br>
          <a href="our_team.html">Our Team</a>
        </div>

        <div class="footer-column">
          <h3>Support Us</h3>
          <a href="locate_us.html">Locate Us</a><br>
          <a href="contact.html">Contact Us</a><br>
          <a href="join_us.html">Join Us</a>
        </div>

        <div class="footer-column">
          <h3>Sign Up for Our Newsletter</h3>
          <p>Want to stay in the loop for our newest bentos with exclusive promo codes?</p>
          <form>
            <input type="email" placeholder="Your email here">
            <button>→</button>
          </form>
        </div>
      </div>

      <div class="copywrite">
        <br><p>© 2025 Koh Kai En Jonathan & Chye Qing Yi Adeline. All rights reserved. Bountiful Bentos Co.</p>
      </div>
    </footer>
  </body>