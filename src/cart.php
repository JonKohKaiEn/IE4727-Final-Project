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
        <img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Bountiful Bentos Logo" class="logo">
        <nav class="nav-links">
          <a href="../src/menu.php">Menu</a>
          <a href="../src/contact.html">Locate Us</a>
        </nav>

        <div class="nav-right">   <!--this is to keep the icons flushed to the right side-->
          <a href="../src/cart.php"><img src="../assets/images/Cart_BB.png" alt="Cart" class="nav-icon"></a>
          <a href="../src/login.html"><img src="../assets/images/User_BB.png" alt="User" class="nav-icon"></a>
        </div>
      </div>
    </header>

    <!-- CART -->
    <section class="cart-container">
      <h2>My Cart</h2>
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

          $gst = $subtotal * 0.07; // Changed from 0.09 to 0.07 to match GST rate
          $total = $subtotal + $gst;

          echo '<div class="total-section">';
          echo '<h3>Order Summary</h3>';
          echo '<p>Subtotal: $' . number_format($subtotal, 2) . '</p>';
          echo '<p>GST (9%): $' . number_format($gst, 2) . '</p>';
          echo '<p><strong>Total: $' . number_format($total, 2) . '</strong></p>';
          echo '<form method="post" action="checkout.php">';
          echo '<button type="submit" class="checkout-btn">Proceed to Checkout</button>';
          echo '</form>';
          echo '</div>';
      }
      ?>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="footer-container">
        <div><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Bountiful Bentos Logo" class="footer-logo"></div>
        <div>
          <h4>About Us</h4>
          <ul><li>Our Team</li><li>Our Story</li></ul>
        </div>

        <div>
          <h4>Support Us</h4>
          <ul><li>Location</li><li>Contact</li><li>Join Us</li></ul>
        </div>

        <div>
          <h4>Sign Up for Our Newsletter</h4>
          <form id="newsletterForm">
            <input type="email" placeholder="Email address" required>
            <button type="submit">→</button>
          </form>
        </div>
      </div>
    </footer>

  </body>
</html>