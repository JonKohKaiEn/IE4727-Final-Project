<?php
session_start();

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if item_name exists in POST data
    if (isset($_POST['item_name'])) {
        $item_name = $_POST['item_name'];
        
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                if (isset($item['name']) && $item['name'] === $item_name) {
                    if (isset($_POST['increase'])) {
                        $_SESSION['cart'][$key]['quantity']++;
                    } elseif (isset($_POST['decrease'])) {
                        $_SESSION['cart'][$key]['quantity']--;
                        if ($_SESSION['cart'][$key]['quantity'] <= 0) {
                            unset($_SESSION['cart'][$key]);
                            $_SESSION['cart'] = array_values($_SESSION['cart']);
                        }
                    }
                    break;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Bountiful Bentos Co.</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Concert+One&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styles.css">
    <link rel="stylesheet" href="../styles/cart.css">
  </head>
  <body>
    <!-- NAVIGATION -->
    <header class="navbar">
      <div class="nav-container">
        <a href="../index.php"><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo"></a>

        <nav class="nav-links">
          <a href="menu.php">Menu</a>
          <a href="locate_us.html">Locate Us</a>
        </nav>

        <div class="nav-icons">
          <a href="cart.php"><img src="../assets/images/Cart_BB.png" alt="Cart" class="icon"></a>
          <a href="login.html"><img src="../assets/images/User_BB.png" alt="Login" class="icon"></a>
        </div>
      </div>
    </header>

    <!-- CART -->
    <section class="cart-container">
      <h2>My Cart</h2>
      <?php
      if (isset($_GET['order'])) {
      	$status = $_GET['order'];
      	if ($status === 'success') {
      		echo '<div style="margin:10px 0;padding:12px;border-radius:6px;background:#e8f5e9;color:#256029;">Your order has been submitted successfully.</div>';
      	} elseif ($status === 'error') {
      		echo '<div style="margin:10px 0;padding:12px;border-radius:6px;background:#fdecea;color:#611a15;">There was an error submitting your order. Please try again.</div>';
      	} elseif ($status === 'empty') {
      		echo '<div style="margin:10px 0;padding:12px;border-radius:6px;background:#fff8e1;color:#7a4f01;">Your cart is empty.</div>';
      	}
      }
      ?>
      <?php
      if(empty($_SESSION['cart'])) {
          echo '<div class="empty-cart"><p>Your cart is empty</p></div>';
      } else {
          foreach($_SESSION['cart'] as $item) {
              if(isset($item['name']) && isset($item['price']) && isset($item['quantity']) && isset($item['image'])) {
                  echo '<div class="cart-item">';
                  echo '<img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['name']) . '">';
                  echo '<div class="item-details">';
                  echo '<h3>' . htmlspecialchars($item['name']) . '</h3>';
                  echo '<p>Price: $' . number_format($item['price'], 2) . '</p>';
                  echo '<div class="quantity-controls">';
                  echo '<form method="post" action="cart.php" style="display:inline;">';
                  echo '<input type="hidden" name="item_name" value="' . htmlspecialchars($item['name']) . '">';
                  echo '<button type="submit" name="decrease" class="qty-btn">-</button>';
                  echo '<input type="text" value="' . $item['quantity'] . '" class="qty-input" readonly>';
                  echo '<button type="submit" name="increase" class="qty-btn">+</button>';
                  echo ' Total: $' . number_format($item['price'] * $item['quantity'], 2);
                  echo '</form>';
                  echo '</div></div></div>';
              }
          }

          // Calculate totals only if there are valid items
          $subtotal = array_reduce($_SESSION['cart'], function($carry, $item) {
              if(isset($item['price']) && isset($item['quantity'])) {
                  return $carry + ($item['price'] * $item['quantity']);
              }
              return $carry;
          }, 0);

          $gst = $subtotal * 0.09;
          $total = $subtotal + $gst;

          echo '<div class="total-section">';
          echo '<h3>Order Summary</h3>';
          echo '<p>Subtotal: $' . number_format($subtotal, 2) . '</p>';
          echo '<p>GST (9%): $' . number_format($gst, 2) . '</p>';
          echo '<p><strong>Total: $' . number_format($total, 2) . '</strong></p>';
          echo '<form method="post" action="../api/submit_order.php">';
          echo '<button type="submit" class="checkout-btn">Proceed to Checkout</button>';
          echo '</form>';
          echo '</div>';
      }
      ?>
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
          <a href="locate_us.html">Locate Us</a><br>
          <a href="contact.html">Contact Us</a><br>
          <a href="join_us.html">Join Us</a>
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
</html>