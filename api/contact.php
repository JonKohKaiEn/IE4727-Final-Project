<?php
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $message = trim($_POST['message'] ?? '');

    $errors = [];

    if (!preg_match("/^[A-Za-z\s\-\/]+$/", $name)) $errors[] = "Full Name contains invalid characters.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid Email format.";
    if ($purpose === "") $errors[] = "Purpose of enquiry cannot be empty.";
    if ($message === "") $errors[] = "Message cannot be empty.";

    if (count($errors) > 0) {
        echo "Errors:\n• " . implode("\n• ", $errors);
        exit;
    }

    // Store in database instead of sending email
    $stmt = $conn->prepare("INSERT INTO contact_submissions (name, email, purpose, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $purpose, $message);

    if ($stmt->execute()) {
        echo "Your enquiry has been submitted successfully! We will get back to you within 3 working days.";
    } else {
        echo "Failed to submit your enquiry. Please try again later.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
