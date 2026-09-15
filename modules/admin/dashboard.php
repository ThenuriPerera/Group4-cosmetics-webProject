<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role(['admin']);

$totalOrders = (int) $pdo
    ->query("SELECT COUNT(*) FROM `Order`")
    ->fetchColumn();

$totalRevenue = (float) $pdo
    ->query("
        SELECT COALESCE(SUM(total_amount), 0)
        FROM `Order`
        WHERE order_status != 'Cancelled'
    ")
    ->fetchColumn();

$totalCustomers = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM `User`
        WHERE role = 'customer'
    ")
    ->fetchColumn();

$totalProducts = (int) $pdo
    ->query("SELECT COUNT(*) FROM Product")
    ->fetchColumn();

$totalCategories = (int) $pdo
    ->query("SELECT COUNT(*) FROM Category")
    ->fetchColumn();

$totalBrands = (int) $pdo
    ->query("SELECT COUNT(*) FROM Brand")
    ->fetchColumn();

$pendingReviews = (int) $pdo
    ->query("
        SELECT COUNT(*)
        FROM Review
        WHERE status = 'Pending'
    ")
    ->fetchColumn();

$orderStatuses = [
    'Pending' => 0,
    'Processing' => 0,
    'Shipped' => 0,
    'Delivered' => 0,
    'Cancelled' => 0
];

$statusRows = $pdo
    ->query("
        SELECT order_status, COUNT(*) AS total
        FROM `Order`
        GROUP BY order_status
    ")
    ->fetchAll();

foreach ($statusRows as $row) {
    $status = $row['order_status'];

    if (isset($orderStatuses[$status])) {
        $orderStatuses[$status] = (int) $row['total'];
    }
}

$paymentStatuses = [
    'Pending' => 0,
    'Completed' => 0,
    'Failed' => 0
];

$paymentRows = $pdo
    ->query("
        SELECT status, COUNT(*) AS total
        FROM Payment
        GROUP BY status
    ")
    ->fetchAll();

foreach ($paymentRows as $row) {
    $status = $row['status'];

    if (isset($paymentStatuses[$status])) {
        $paymentStatuses[$status] = (int) $row['total'];
    }
}

$trendingProducts = $pdo
    ->query("
        SELECT
            p.product_name,
            SUM(oi.quantity) AS units_sold
        FROM Order_Item oi
        INNER JOIN Product p
            ON p.product_id = oi.product_id
        INNER JOIN `Order` o
            ON o.order_id = oi.order_id
        WHERE o.order_status != 'Cancelled'
        GROUP BY p.product_id, p.product_name
        ORDER BY units_sold DESC
        LIMIT 5
    ")
    ->fetchAll();

$recentOrders = $pdo
    ->query("
        SELECT
            o.order_id,
            o.total_amount,
            o.order_status,
            o.order_date,
            u.name AS customer_name
        FROM `Order` o
        INNER JOIN `User` u
            ON u.user_id = o.user_id
        ORDER BY o.order_date DESC
        LIMIT 8
    ")
    ->fetchAll();

$recentPayments = $pdo
    ->query("
        SELECT
            p.payment_id,
            p.order_id,
            p.transaction_id,
            p.method,
            p.amount,
            p.status,
            p.payment_date
        FROM Payment p
        ORDER BY p.payment_date DESC
        LIMIT 8
    ")
    ->fetchAll();

$pageKey = 'admin/dashboard';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/admin/dashboard.view.php';
require_once __DIR__ . '/../../includes/footer.php';