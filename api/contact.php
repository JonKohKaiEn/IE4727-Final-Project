<?php
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

    $to = "f31ee@localhost";
    $subject = "Contact Us Enquiry: $purpose";
    $body = "Name: $name\nEmail: $email\nPurpose: $purpose\nMessage:\n$message";

    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Your enquiry has been submitted successfully!";
    } else {
        echo "Failed to submit your enquiry. Please try again later.";
    }
} else {
    echo "Invalid request.";
}
?>
