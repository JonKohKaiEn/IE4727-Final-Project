<?php
include_once 'db_connect.php';

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$birthday = $_POST['birthday'];
$phone = trim($_POST['phone']);
$username = trim($_POST['username']);
$password = $_POST['password'];
$marketing_opt_in = isset($_POST['marketing_opt_in']) ? 1 : 0;

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Server-side duplicate check
$stmt = $conn->prepare("SELECT * FROM members WHERE username=? OR email=?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Username or email already exists.<br>";
    exit;
}
$stmt->close();

$stmt = $conn->prepare("INSERT INTO members (full_name,email,birthday,phone,username,password_hash,marketing_opt_in) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssi", $full_name, $email, $birthday, $phone, $username, $password_hash, $marketing_opt_in);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Failed to create account. Try again.<br>";
}

$stmt->close();
$conn->close();
?>
