<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/db_connect.php';

$res = $conn->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 8");
$reviews = [];
while ($row = $res->fetch_assoc()) $reviews[] = $row;

echo json_encode($reviews, JSON_UNESCAPED_UNICODE);
?>