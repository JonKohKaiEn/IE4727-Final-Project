<?php
include_once __DIR__ . '/../api/db_connect.php';

// Initialize variables for popup messages
$message = '';
$message_type = 'error';

// Handle all form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- HANDLE 'ADD NEW' ACTION ---
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Retrieve and sanitize data
        $name = trim($_POST['name']);
        $category = trim($_POST['category']);
        $price = trim($_POST['price']);
        $description = trim($_POST['description']);

        // Server-side validation
        if (empty($name) || empty($category) || empty($price) || empty($description)) {
            $message = "Incomplete fields. Please fill out all cells to add an item.";
        } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $price) || (float)$price < 0) {
            $message = "Invalid price. Please enter a positive number (e.g., 6.50 or 7).";
        } else {
            // All valid, proceed with insert using prepared statement
            $stmt = $conn->prepare("INSERT INTO homeproducts (name, category, price, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssds", $name, $category, $price, $description);
            
            if ($stmt->execute()) {
                $message = "Successfully added '$name'!";
                $message_type = 'success';
            } else {
                $message = "Error adding item: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Handles update action
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);

    // Server-side validation
    if (empty($name) || empty($category) || empty($price) || empty($description) || empty($id)) {
        $message = "Incomplete edits, ensure all cells are filled.";
    } elseif (!preg_match('/^\d+(\.\d{1,2})?$/', $price) || (float)$price < 0) {
        $message = "Invalid price. Must be a number with up to 2 decimal places.";
    } else {
        // All valid, proceed with update
        $stmt = $conn->prepare("UPDATE homeproducts SET name = ?, category = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $name, $category, $price, $description, $id);
            
        if ($stmt->execute()) {
            $message = "Successfully updated item #$id!";
            $message_type = 'success';
        } else {
            $message = "Error updating item: " . $conn->error;
        }
        $stmt->close();
    }
}

// Handles delete action
if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $id = $_POST['id'];

    if (empty($id)) {
        $message = "Error: No ID provided for deletion.";
    } else {
        $stmt = $conn->prepare("DELETE FROM homeproducts WHERE id = ?");
        $stmt->bind_param("i", $id);
            
        if ($stmt->execute()) {
            $message = "Successfully deleted item #$id.";
            $message_type = 'success';
        } else {
            $message = "Error deleting item: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetches and displays updated groups of items
$result = $conn->query("SELECT * FROM homeproducts ORDER BY category, name");

$grouped_products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grouped_products[$row['category']][] = $row;
    }
}

// Define categories from your ENUM for dropdowns
$categories = ['promotion', 'classic', 'sides', 'drinks', 'desserts'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bountiful Bentos | Menu Management</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Concert+One&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="../styles/styles.css">
        <link rel="stylesheet" href="../styles/manage_bentos.css">
    </head>

    <body>
        <!-- NAVIGATION -->
        <header class="navbar">
            <div class="nav-container">
                <a href="../index.php"><img src="../assets/images/BountifulBentos_Logo_Cream.png" alt="Logo" class="logo"></a>

                <nav class="nav-links">
                    <a href="dashboard.php">Dashboard</a>
                    <a href="manage_bentos.php">Manage Bentos</a>
                    <a href="admin.html">Log Out</a>
                </nav>
            </div>
        </header>

        <!-- MAIN CONTENT -->
        <main id="main-admin">
            <h2>Add New Bento</h2>
            <form action="manage_bentos.php" method="POST" class="add-item-form">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="add-name">Name:</label>
                    <input type="text" id="add-name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="add-category">Category:</label>
                    <select id="add-category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="add-price">Price:</label>
                    <input type="number" id="add-price" name="price" step="0.01" min="0" pattern="\d+(\.\d{1,2})?" required>
                </div>

                <div class="form-group form-group-full">
                    <label for="add-description">Description:</label>
                    <textarea id="add-description" name="description" rows="3" required></textarea>
                </div>

                <div class="form-group-full">
                    <button type="submit" class="btn btn-add">Add New Item</button>
                </div>
            </form>

            <hr class="separator">
            <h2>Manage Existing Bentos</h2>

            <?php if (empty($grouped_products)): ?>
                <p>No products found in the database.</p>    
            <?php else: ?>
                <?php foreach ($grouped_products as $category => $items): ?>
                    <h3><?php echo ucfirst(htmlspecialchars($category)); ?></h3>
                    
                    <div class="table-wrapper">
                        <table class="manage-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th class="col-desc">Description</th>
                                    <th class="col-actions">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <form action="manage_bentos.php" method="POST" class="update-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                            
                                            <td>
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                                                
                                            </td>

                                            <td>
                                                <select name="category" required>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat; ?>" <?php if ($item['category'] == $cat) echo 'selected'; ?>>
                                                            <?php echo ucfirst($cat); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" step="0.01" min="0" pattern="\d+(\.\d{1,2})?" required>
                                            </td>

                                            <td>
                                                <textarea name="description" rows="3" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                                            </td>

                                            <td>
                                                <button type="submit" class="btn btn-update">Update</button>
                                                
                                                <form action="manage_bentos.php" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" class="btn btn-delete">Delete</button>
                                                </form>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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