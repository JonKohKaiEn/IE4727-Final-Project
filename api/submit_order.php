<?php
session_start();

// Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: ../src/cart.php');
	exit;
}

// Ensure there are items in the cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
	header('Location: ../src/cart.php?order=empty');
	exit;
}

require_once __DIR__ . '/db_connect.php';

// Compute totals (mirror logic in cart.php)
$subtotal = 0.0;
foreach ($_SESSION['cart'] as $item) {
	if (isset($item['price']) && isset($item['quantity'])) {
		$subtotal += ((float)$item['price']) * ((int)$item['quantity']);
	}
}

$gst = $subtotal * 0.09;
$total = $subtotal + $gst;

// Basic guard
if ($total <= 0) {
	header('Location: ../src/cart.php?order=empty');
	exit;
}

// Optional: fetch basic customer info from POST if added later
$customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : null;
$customer_email = isset($_POST['email']) ? trim($_POST['email']) : null;

// Validate email
if (!$customer_email || !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../src/checkout.php?error=invalid_email');
    exit;
}

// Use a transaction to ensure consistency
$conn->begin_transaction();

try {
	// Insert into orders
	$orderSql = "INSERT INTO orders (customer_name, customer_email, subtotal, gst, total) VALUES (?, ?, ?, ?, ?)";
	$orderStmt = $conn->prepare($orderSql);
	if (!$orderStmt) {
		throw new Exception('Failed to prepare order insert.');
	}
	$orderStmt->bind_param(
		"ssddd",
		$customer_name,
		$customer_email,
		$subtotal,
		$gst,
		$total
	);
	if (!$orderStmt->execute()) {
		throw new Exception('Failed to execute order insert.');
	}
	$orderId = $orderStmt->insert_id;
	$orderStmt->close();

	// Insert order items
	$itemSql = "INSERT INTO order_items (order_id, product_name, unit_price, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
	$itemStmt = $conn->prepare($itemSql);
	if (!$itemStmt) {
		throw new Exception('Failed to prepare order_items insert.');
	}

	foreach ($_SESSION['cart'] as $item) {
		if (!isset($item['name'], $item['price'], $item['quantity'])) {
			continue;
		}
		$product_name = (string)$item['name'];
		$unit_price = (float)$item['price'];
		$quantity = (int)$item['quantity'];
		$total_price = $unit_price * $quantity;

		if ($quantity <= 0) {
			continue;
		}

		$itemStmt->bind_param(
			"isdid",
			$orderId,
			$product_name,
			$unit_price,
			$quantity,
			$total_price
		);
		if (!$itemStmt->execute()) {
			throw new Exception('Failed to execute order_items insert.');
		}
	}

	$itemStmt->close();

	// Commit transaction
	$conn->commit();

	// Clear cart and redirect with success flag
	unset($_SESSION['cart']);
	header('Location: ../src/cart.php?order=success');
	exit;
} catch (Throwable $e) {
	$conn->rollback();
	// You might log $e->getMessage() in a real app
	header('Location: ../src/cart.php?order=error');
	exit;
}


