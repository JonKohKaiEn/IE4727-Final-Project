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

	// Store cart items for email before clearing
	$cartItems = $_SESSION['cart'];

	// Send order confirmation email
	// $to = $customer_email;
	$to = 'f32ee@localhost';
	$subject = "Order Confirmation - Bountiful Bentos Co. (Order #" . $orderId . ")";
	
	// Build email body
	$emailBody = "Dear " . ($customer_name ? $customer_name : "Valued Customer") . ",\n\n";
	$emailBody .= "Thank you for your order! We're excited to prepare your delicious bento.\n\n";
	$emailBody .= "ORDER DETAILS\n";
	$emailBody .= "=============\n";
	$emailBody .= "Order Number: #" . $orderId . "\n";
	$emailBody .= "Order Date: " . date('F j, Y g:i A') . "\n\n";
	
	$emailBody .= "ORDER ITEMS\n";
	$emailBody .= "-----------\n";
	foreach ($cartItems as $item) {
		if (isset($item['name'], $item['price'], $item['quantity'])) {
			$itemTotal = (float)$item['price'] * (int)$item['quantity'];
			$emailBody .= $item['name'] . " x" . $item['quantity'] . " - $" . number_format($itemTotal, 2) . "\n";
		}
	}
	
	$emailBody .= "\n";
	$emailBody .= "Subtotal: $" . number_format($subtotal, 2) . "\n";
	$emailBody .= "GST (9%): $" . number_format($gst, 2) . "\n";
	$emailBody .= "TOTAL: $" . number_format($total, 2) . "\n\n";
	
	$emailBody .= "We'll send you another email when your order is ready for pickup or delivery.\n\n";
	$emailBody .= "Thank you for choosing Bountiful Bentos Co.!\n\n";
	$emailBody .= "Best regards,\n";
	$emailBody .= "Bountiful Bentos Co. Team\n";
	
	// Email headers
	$headers = "From: Bountiful Bentos Co. <noreply@bountifulbentos.com>\r\n";
	$headers .= "Reply-To: noreply@bountifulbentos.com\r\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	
	// Send email (non-blocking - order success doesn't depend on email)
	mail($to, $subject, $emailBody, $headers, '-ff32ee@localhost');

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


