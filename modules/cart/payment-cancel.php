<?php
/* Shown when Stripe checkout is cancelled or payment fails.
 The Order row stays 'Pending' and the cart is left intact so the user can retry.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$orderId = $_GET['order_id'] ?? null;
// Presentation is kept in views/cart/payment-cancel.view.php.
$pageKey = 'cart/payment-cancel';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/cart/payment-cancel.view.php';
require_once __DIR__ . '/../../includes/footer.php';
