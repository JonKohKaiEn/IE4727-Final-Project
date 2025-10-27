<?php
header('Content-Type: application/json; charset=utf-8');
include_once __DIR__ . '/db_connect.php';

$category = $_GET['category'] ?? '';

if ($category) {
    $stmt = $conn->prepare("SELECT * FROM homeproducts WHERE category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $conn->query("SELECT * FROM homeproducts");
}

$products = [];
while ($row = $res->fetch_assoc()) $products[] = $row;

echo json_encode($products, JSON_UNESCAPED_UNICODE);
?>