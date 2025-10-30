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
        <a href="../index.html"><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Bountiful Bentos Logo" class="logo"></a>
        <nav class="nav-links">
          <a href="../src/menu.php">Menu</a>
          <a href="../src/contact.html">Locate Us</a>
        </nav>

        <div class="nav-right">   <!--this is to keep the icons flushed to the right side-->
          <a href="../src/cart.html"><img src="../assets/images/Cart_BB.png" alt="Cart" class="nav-icon"></a>
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
                echo "<div class='quantity-controls'>";
                echo "<button class='qty-btn-minus' onclick='decreaseQty(this)'>-</button>";
                echo "<input type='number' value='0' min='0' class='qty-input'>";
                echo "<button class='qty-btn-plus' onclick='increaseQty(this)'>+</button>";
                echo "</div>";
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
          const input = btn.previousElementSibling;
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