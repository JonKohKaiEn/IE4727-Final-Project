<?php
include_once __DIR__ . '/db_connect.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);

  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $stmt = $conn->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (?)");
    $stmt->bind_param('s', $email);
    if ($stmt->execute()) {
      echo json_encode(['success' => true, 'message' => 'Thanks for subscribing!']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    $stmt->close();
  } else {
    echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
  }
}
?>
