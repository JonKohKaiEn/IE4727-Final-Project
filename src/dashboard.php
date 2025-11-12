<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Bountiful Bentos |Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Concert+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles/styles.css">

  <style>
    #main{
        margin-left: 20px;
        padding: 20px;
    }

    #main h3 {
    margin: 0;
    padding: 40px 0; /*centers text vertically*/
    }
  </style>
</head>
<body>
    <!-- NAVIGATION -->
    <header class="navbar">
      <div class="nav-container">
        <a href="index.php"><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo"></a>

        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_bentos.php">Manage Bentos</a>
            <a href="admin.html">Log Out</a>
        </nav>
      </div>
    </header>

    <!-- MAIN CONTENT -->
    <main id="main">
      <h2>Welcome, Admin!</h2>
      <p>Use the navigation menu to manage menu items.</p>
    </main>

    <!-- FOOTER -->
    <footer>
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