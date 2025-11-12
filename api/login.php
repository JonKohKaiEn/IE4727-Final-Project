<?php
session_start();
include_once 'db_connect.php';

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $_SESSION['user'] = [
        'id' => $row['member_id'],
        'username' => $row['username']
    ];
    header("Location: ../src/member.php");  // Changed from member.html to member.php
    exit();
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 3) {
    echo "locked";
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT password_hash FROM members WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($hash);
if ($stmt->fetch() && password_verify($password, $hash)) {
    $_SESSION['login_attempts'] = 0;
    echo "success";
} else {
    $_SESSION['login_attempts']++;
    echo "fail";
}
$stmt->close();
$conn->close();
?>
