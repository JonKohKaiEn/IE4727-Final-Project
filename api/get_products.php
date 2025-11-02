<?php
include_once "db_connect.php";

$query = "SELECT * FROM products";
$result = $conn->query($query);

$products = [];
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>
