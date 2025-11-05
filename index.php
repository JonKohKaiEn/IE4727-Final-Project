<?php include_once __DIR__ . '/api/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
          <a href="src/contact.html">Contact Us</a>
        </nav>

        <div class="nav-icons">
          <a href="src/cart.php"><img src="assets/images/Cart_BB.png" alt="Cart"></a>
          <a href="src/login.html"><img src="assets/images/User_BB.png" alt="Login"></a>
        </div>
      </div>
     </header>

    <!-- ===== HERO SECTION, MAIN PIC ===== -->
    <section class="hero">
      <img src="assets/images/Home_MainImg.jpg" alt="Bento Background" class="hero-img">
      <div class="hero-text">
        <h1>Bountiful Bentos</h1>
        <p>Nutritious Bentos to Fuel Your Busy Days</p>
        <a href="src/menu.php"><button class="order-btn">Order Now</button></a>
      </div>
    </section>

    <!-- ===== PRODUCT CAROUSELS ===== -->
    <section class="carousel-section">
       <!-- === OFFERS === -->
        <h2>Offers</h2>
        <div class="carousel-center">
          <div class="carousel" id="offers-carousel">
            <?php
              include_once __DIR__ . '/api/db_connect.php';
              $offersQuery = "SELECT * FROM homeproducts WHERE id BETWEEN 1 AND 3";
              $offersResult = $conn->query($offersQuery);

              while ($offer = $offersResult->fetch_assoc()) {
                echo "
                  <div class='product-card'>
                    <div class='card-image'>
                      <img src='{$offer['image_url']}' alt='{$offer['name']}'>
                    </div>
                    <div class='card-info'>
                      <p class='product-name'><strong>{$offer['name']}</strong></p>
                      <p class='product-price'>\$ {$offer['price']}</p>
                      <p class='product-desc'>{$offer['description']}</p>
                    </div>
                  </div>
                ";
              }
            ?>
          </div>
        </div>

        <!-- === NEW PRODUCTS === -->
        <h2>New</h2>
        <div class="carousel-container" id="new-container">
          <button class="carousel-arrow left" onclick="scrollCarousel('new-carousel', -1)">&#10094;</button>

          <div class="carousel" id="new-carousel">
            <?php
              $newQuery = "SELECT * FROM homeproducts WHERE id BETWEEN 4 AND 9";
              $newResult = $conn->query($newQuery);

              while ($new = $newResult->fetch_assoc()) {
                echo "
                  <div class='product-card'>
                    <div class='card-image'>
                      <img src='{$new['image_url']}' alt='{$new['name']}'>
                    </div>
                    <div class='card-info'>
                      <p class='product-name'><strong>{$new['name']}</strong></p>
                      <p class='product-price'>\$ {$new['price']}</p>
                      <p class='product-desc'>{$new['description']}</p>
                    </div>
                  </div>
                ";
              }
            ?>
          </div>
          <button class="carousel-arrow right" onclick="scrollCarousel('new-carousel', 1)">&#10095;</button>
        </div>
    </section>

    <!-- ===== REVIEWS ===== -->
    <section class="reviews">
      <h2>What Our Customers Say</h2>

      <div class="review-carousel-container">
        <button class="review-btn left" onclick="scrollReviews(-1)">&#10094;</button>

        <div class="review-carousel" id="review-carousel">
          <?php
          include_once __DIR__ . '/api/db_connect.php';
          $query = "SELECT name, rating, comment FROM reviews";
          $result = $conn->query($query);
          
          if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $stars = str_repeat('⭐', floor($row['rating']));
              echo "
              <div class='review-card'>
              <table class='review-table'>
              <tr>
                <td class='rating-cell'>{$stars}</td>
              </tr>
              <tr>
                <td class='username-cell'><strong>{$row['name']}</strong></td>
                <td class='comment-cell'><em>{$row['comment']}</em></td>
              </tr>
            </table>
          </div>";
        }
      } else {
        echo "<p>No reviews yet.</p>";
      }
      ?>
        </div>

        <button class="review-btn right" onclick="scrollReviews(1)">&#10095;</button>
      </div>
      <script defer src="js/review-carousel.js"></script>
    </section>


    <!-- ===== FOOTER ===== -->
    <footer>
      <div class="footer-container">
        <div class="footer-column">
          <img src="assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo">
        </div>

        <div class="footer-column">
          <h3>About Us</h3>
          <a href="src/our_story.html">Our Story</a><br>
          <a href="src/our_team.html">Our Team</a>
        </div>

        <div class="footer-column">
          <h3>Support Us</h3>
          <a href="src/contact.html">Contact Us</a><br>
        </div>

        <div class="footer-column">
          <h3>Sign Up for Our Newsletter</h3>
          <p>Want to stay in the loop for our newest bentos with exclusive promo codes?</p>
          <form id="newsletterForm">
            <input type="email" id="newsletterEmail" placeholder="Your email here" required>
              <button type="submit">→</button>
          </form>

          <!-- Popup Notification -->
          <div id="popup" class="popup hidden">
            <div class="popup-content">
              <p id="popupMessage"></p>
            </div>
          </div>

          <script>
            document.getElementById("newsletterForm").addEventListener("submit", async function(e) {
              e.preventDefault();

              const email = document.getElementById("newsletterEmail").value;
              const formData = new FormData();
              formData.append("email", email);

              const response = await fetch("api/newsletter_signup.php", {
                method: "POST",
                body: formData
              });
              
              const result = await response.json();
              showPopup(result.message, result.success);

              document.getElementById("newsletterEmail").value = "";
            });

            function showPopup(message, success) {
              const popup = document.getElementById("popup");
              const popupMessage = document.getElementById("popupMessage");

              popupMessage.textContent = message;
              popup.classList.remove("hidden", "error", "show");
              if (!success) popup.classList.add("error");

              setTimeout(() => popup.classList.add("show"), 50); // trigger transition
              setTimeout(() => {
              popup.classList.remove("show");
              setTimeout(() => popup.classList.add("hidden"), 300);
              }, 3000); // hides after 3 seconds
            }
          </script>
        </div>
      </div>

      <div class="copywrite">
        <br><p>© 2025 Koh Kai En Jonathan & Chye Qing Yi Adeline. All rights reserved. Bountiful Bentos Co.</p>
      </div>
    </footer>
  </body>