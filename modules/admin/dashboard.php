<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['admin']);

$totalOrders = $pdo->query("SELECT COUNT(*) FROM `Order`")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM `Order` WHERE order_status != 'Cancelled'")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM User WHERE role = 'customer'")->fetchColumn();
$pendingReviewCount = $pdo->query("SELECT COUNT(*) FROM Review WHERE status = 'Pending'")->fetchColumn();

// Trending products: most units sold across all orders
$trending = $pdo->query(
    "SELECT p.product_name, SUM(oi.quantity) AS units_sold
     FROM Order_Item oi JOIN Product p ON oi.product_id = p.product_id
     GROUP BY oi.product_id ORDER BY units_sold DESC LIMIT 5"
)->fetchAll();

// Recent payments for monitoring
$payments = $pdo->query(
    "SELECT pay.*, o.user_id FROM Payment pay JOIN `Order` o ON pay.order_id = o.order_id
     ORDER BY pay.payment_date DESC LIMIT 10"
)->fetchAll();

// Presentation is kept in views/admin/dashboard.view.php.
$pageKey = 'admin/dashboard';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/admin/dashboard.view.php';
require_once __DIR__ . '/../../includes/footer.php';
