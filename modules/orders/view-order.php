<?php
/**
 * Lumine Glow - Customer Order Details
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$userId = current_user()['user_id'];
$orderId = (int) ($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    http_response_code(400);
    die('Invalid order ID.');
}

/* Get order */
$orderStmt = $pdo->prepare(
    "SELECT
        o.*,
        a.street,
        a.city,
        a.postal_code,
        a.state,
        a.country
     FROM `Order` AS o
     LEFT JOIN Address AS a
        ON a.address_id = o.address_id
     WHERE o.order_id = ?
       AND o.user_id = ?"
);

$orderStmt->execute([$orderId, $userId]);
$order = $orderStmt->fetch();

if (!$order) {
    http_response_code(404);
    die('Order not found.');
}

/* Get order items */
$itemStmt = $pdo->prepare(
    "SELECT
        oi.order_item_id,
        oi.product_id,
        oi.quantity,
        oi.price,
        p.product_name,
        p.image,
        p.short_description
     FROM Order_Item AS oi
     INNER JOIN Product AS p
        ON p.product_id = oi.product_id
     WHERE oi.order_id = ?
     ORDER BY oi.order_item_id ASC"
);

$itemStmt->execute([$orderId]);
$orderItems = $itemStmt->fetchAll();

/* Get shipment */
$shipmentStmt = $pdo->prepare(
    "SELECT
        s.*,
        c.company_name,
        c.contact_number
     FROM Shipment AS s
     LEFT JOIN Courier AS c
        ON c.courier_id = s.courier_id
     WHERE s.order_id = ?
     LIMIT 1"
);

$shipmentStmt->execute([$orderId]);
$shipment = $shipmentStmt->fetch();

/* Get order history */
$historyStmt = $pdo->prepare(
    "SELECT
        order_history_status,
        time_stamp
     FROM Order_History
     WHERE order_id = ?
     ORDER BY time_stamp ASC"
);

$historyStmt->execute([$orderId]);
$orderHistory = $historyStmt->fetchAll();

$pageKey = 'orders/view-order';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/orders/view-order.view.php';

require_once __DIR__ . '/../../includes/footer.php';