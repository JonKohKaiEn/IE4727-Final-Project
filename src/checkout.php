<?php
session_start();

// Redirect if cart is empty
if(empty($_SESSION['cart'])) {
    header('Location: cart.php?order=empty');
    exit();
}

// Calculate order total
$subtotal = array_reduce($_SESSION['cart'], function($carry, $item) {
    if(isset($item['price']) && isset($item['quantity'])) {
        return $carry + ($item['price'] * $item['quantity']);
    }
    return $carry;
}, 0);

$gst = $subtotal * 0.09;
$total = $subtotal + $gst;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Checkout - Bountiful Bentos Co.</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Concert+One&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .checkout-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .order-summary {
            background-color: #f5f5f5;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
        }
        
        .email-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .email-input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        
        .submit-btn {
            background-color: #5C2700;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        
        .submit-btn:hover {
            background-color: #7B3300;
        }
    </style>
  </head>
  <body>
    <!-- NAVIGATION -->
    <header class="navbar">
      <div class="nav-container">
        <a href="../index.php"><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo"></a>

        <nav class="nav-links">
          <a href="menu.php">Menu</a>
          <a href="contact.html">Contact Us</a>
        </nav>

        <div class="nav-icons">
          <a href="cart.php"><img src="../assets/images/Cart_BB.png" alt="Cart" class="icon"></a>
          <a href="login.html"><img src="../assets/images/User_BB.png" alt="Login" class="icon"></a>
        </div>
      </div>
    </header>

    <!-- CHECKOUT FORM -->
    <section class="checkout-container">
        <div class="order-summary">
            <h2>Order Summary</h2>
            <p>Subtotal: $<?php echo number_format($subtotal, 2); ?></p>
            <p>GST (9%): $<?php echo number_format($gst, 2); ?></p>
            <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
        </div>

        <div class="email-form">
            <h3>Enter Your Email for Order Updates</h3>
            <p>We'll send you updates about your order status</p>
            <form method="post" action="../api/submit_order.php">
                <input type="email" name="email" class="email-input" placeholder="Enter your email" required>
                <button type="submit" class="submit-btn">Complete Order</button>
            </form>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-column">
          <img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo">
        </div>

        <div class="footer-column">
          <h3>About Us</h3>
          <a href="our_story.html">Our Story</a><br>
          <a href="our_team.html">Our Team</a>
        </div>

        <div class="footer-column">
          <h3>Support Us</h3>
          <a href="contact.html">Contact Us</a><br>
        </div>

        <div class="footer-column">
          <h3>Sign Up for Our Newsletter</h3>
          <p>Want to stay in the loop for our newest bentos with exclusive promo codes?</p>
          <form id="newsletterForm">
            <input type="email" id="newsletterEmail" placeholder="Your email here" required>
            <button type="submit">→</button>
          </form>

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
              const response = await fetch("../api/newsletter_signup.php", {
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
              setTimeout(() => popup.classList.add("show"), 50);
              setTimeout(() => {
                popup.classList.remove("show");
                setTimeout(() => popup.classList.add("hidden"), 300);
              }, 3000);
            }
          </script>
        </div>
      </div>

      <div class="copywrite">
        <br><p>© 2025 Koh Kai En Jonathan & Chye Qing Yi Adeline. All rights reserved. Bountiful Bentos Co.</p>
      </div>
    </footer>
  </body>
</html>