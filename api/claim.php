<?php
session_start();
require_once 'db_connect.php';

// Add debugging for session
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

// Debug session info
if (empty($_SESSION)) {
    echo 'error:Session empty';
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo 'error:Please login first';
    exit;
}

if (!isset($_POST['voucher_id'])) {
    echo 'error:Invalid request';
    exit;
}

$user_id = $_SESSION['user_id'];
$voucher_id = $_POST['voucher_id'];

// Check if user already claimed this voucher
$stmt = $conn->prepare("SELECT * FROM ewallet WHERE user_id = ? AND voucher_id = ? AND is_used = 0");
$stmt->bind_param("ii", $user_id, $voucher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo 'error:You have already claimed this voucher';
    exit;
}

// Add voucher to e-wallet
$stmt = $conn->prepare("INSERT INTO ewallet (user_id, voucher_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $voucher_id);

if ($stmt->execute()) {
    echo 'success:Voucher claimed successfully';
} else {
    echo 'error:Failed to claim voucher';
}

$stmt->close();
$conn->close();
?>