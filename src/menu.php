<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize cart if it doesn't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Validate that required fields exist and have valid values
    if (isset($_POST['item_name']) && 
        isset($_POST['item_price']) && 
        isset($_POST['quantity']) && 
        isset($_POST['item_image']) &&
        $_POST['item_name'] !== '' &&
        is_numeric($_POST['item_price']) &&
        is_numeric($_POST['quantity'])) {
        
        $item_name = htmlspecialchars($_POST['item_name']);
        $item_price = floatval($_POST['item_price']);
        $item_quantity = intval($_POST['quantity']);
        $item_image = htmlspecialchars($_POST['item_image']);

        // Only process if quantity is greater than 0
        if ($item_quantity > 0) {
            $item_exists = false;

            // Check if item already exists in cart
            foreach ($_SESSION['cart'] as &$item) {
                if (isset($item['name']) && $item['name'] === $item_name) {
                    $item['quantity'] += $item_quantity;
                    $item_exists = true;
                    break;
                }
            }
            unset($item); // Unset the reference after the loop

            // Add new item if it doesn't exist
            if (!$item_exists) {
                $_SESSION['cart'][] = array(
                    'name' => $item_name,
                    'price' => $item_price,
                    'quantity' => $item_quantity,
                    'image' => $item_image
                );
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
    <style><?php include '../styles/menu.css' ?></style>
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

    <!-- MENU -->
    <section class="menu">
      <h2>Our Menu</h2>
      <div class="menu-sections">
        <?php
        include_once '../api/db_connect.php';
        
        $categories = ['Promotion', 'Classic', 'Sides', 'Drinks', 'Desserts'];
        foreach($categories as $category) {
            $activeClass = ($category === 'Promotion') ? 'active' : '';
            echo "<button class='category-btn $activeClass' data-category='$category'>$category</button>";
        }
        ?>
      </div>

      <div class="menu-items">
        <?php
        foreach($categories as $category) {
            $sql = "SELECT * FROM homeproducts WHERE category = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $category);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $display = ($category === 'Promotion') ? 'block' : 'none';
            echo "<div class='menu-category' id='$category' style='display: $display;'>";
            echo "<table width='100%' cellpadding='10'>";
            
            $itemCount = 0;
            while($item = $result->fetch_assoc()) {
                if($itemCount % 3 == 0) {
                    if($itemCount > 0) echo "</tr>";
                    echo "<tr>";
                }
                
                echo "<td align='center' width='33%'>";
                echo "<div class='menu-item'>";
                if($item['image_url']) {
                    echo "<img src='{$item['image_url']}' alt='{$item['name']}' style='width:200px;height:200px;'><br>";
                }
                echo "<h3>" . htmlspecialchars($item['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($item['description']) . "</p>";
                echo "<p class='price'>$" . number_format($item['price'], 2) . "</p>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='item_name' value='" . htmlspecialchars($item['name']) . "'>";
                echo "<input type='hidden' name='item_price' value='" . $item['price'] . "'>";
                echo "<input type='hidden' name='item_image' value='" . $item['image_url'] . "'>";
                echo "<div class='quantity-controls'>";
                echo "<button type='button' class='qty-btn-minus' onclick='decreaseQty(this)'>-</button>";
                echo "<input type='number' name='quantity' value='0' min='0' class='qty-input'>";
                echo "<button type='button' class='qty-btn-plus' onclick='increaseQty(this)'>+</button>";
                echo "</div>";
                echo "<button type='submit' class='add-to-cart-btn'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
                echo "</td>";
                
                $itemCount++;
            }
            
            // Fill remaining cells in last row if needed
            while($itemCount % 3 !== 0) {
                echo "<td width='33%'></td>";
                $itemCount++;
            }
            
            echo "</tr>";
            echo "</table>";
            echo "</div>";
        }
        ?>
      </div>
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

    <script>
      document.querySelectorAll('.category-btn').forEach(button => {
          button.addEventListener('click', () => {
              document.querySelectorAll('.category-btn').forEach(btn => {
                  btn.classList.remove('active');
              });
              button.classList.add('active');
              
              document.querySelectorAll('.menu-category').forEach(category => {
                  category.style.display = 'none';
              });
              
              const categoryToShow = document.getElementById(button.dataset.category);
              if(categoryToShow) {
                  categoryToShow.style.display = 'block';
              }
          });
      });

      function increaseQty(btn) {
          const input = btn.nextElementSibling;
          input.value = parseInt(input.value) + 1;
      }

      function decreaseQty(btn) {
          const input = btn.nextElementSibling;
          if(parseInt(input.value) > 0) {
              input.value = parseInt(input.value) - 1;
          }
      }
    </script>
  </body>
</html>