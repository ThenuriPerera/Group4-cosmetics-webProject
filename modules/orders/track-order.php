<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$orderId = $_GET['order_id'] ?? null;

if ($orderId) {
    $stmt = $pdo->prepare("SELECT * FROM `Order` WHERE order_id = ? AND user_id = ?");
    $stmt->execute([$orderId, $userId]);
    $orders = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM `Order` WHERE user_id = ? ORDER BY order_date DESC");
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
}

// Preload history + shipment for each order shown
$historyByOrder = [];
$shipmentByOrder = [];
foreach ($orders as $order) {
    $h = $pdo->prepare("SELECT * FROM Order_History WHERE order_id = ? ORDER BY time_stamp ASC");
    $h->execute([$order['order_id']]);
    $historyByOrder[$order['order_id']] = $h->fetchAll();

    $s = $pdo->prepare(
        "SELECT sh.*, c.company_name, c.contact_number FROM Shipment sh LEFT JOIN Courier c ON sh.courier_id = c.courier_id WHERE sh.order_id = ?"
    );
    $s->execute([$order['order_id']]);
    $shipmentByOrder[$order['order_id']] = $s->fetch();
}

// Presentation is kept in views/orders/track-order.view.php.
$pageKey = 'orders/track-order';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/orders/track-order.view.php';
require_once __DIR__ . '/../../includes/footer.php';
