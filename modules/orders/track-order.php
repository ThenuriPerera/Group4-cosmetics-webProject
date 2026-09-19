<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$userId = (int) current_user()['user_id'];
$orderId = $_GET['order_id'] ?? null;


// ------------------------------------------------------------
// Load customer orders
// ------------------------------------------------------------

if ($orderId) {

    $stmt = $pdo->prepare(
        "SELECT *
         FROM `Order`
         WHERE order_id = ?
         AND user_id = ?"
    );

    $stmt->execute([
        (int) $orderId,
        $userId
    ]);

    $orders = $stmt->fetchAll();

} else {

    $stmt = $pdo->prepare(
        "SELECT *
         FROM `Order`
         WHERE user_id = ?
         ORDER BY order_date DESC"
    );

    $stmt->execute([
        $userId
    ]);

    $orders = $stmt->fetchAll();
}


// ------------------------------------------------------------
// Preload tracking history + shipment information
// ------------------------------------------------------------

$historyByOrder = [];
$shipmentByOrder = [];

foreach ($orders as $order) {

    $currentOrderId = (int) $order['order_id'];


    // --------------------------------------------------------
    // Order history
    // --------------------------------------------------------

    $h = $pdo->prepare(
        "SELECT *
         FROM Order_History
         WHERE order_id = ?
         ORDER BY time_stamp ASC"
    );

    $h->execute([
        $currentOrderId
    ]);

    $historyByOrder[$currentOrderId] = $h->fetchAll(PDO::FETCH_ASSOC);


    // --------------------------------------------------------
    // Shipment + Courier + GPS information
    // --------------------------------------------------------

    $s = $pdo->prepare(
        "SELECT
            sh.*,

            c.company_name,
            c.contact_number

         FROM Shipment sh

         LEFT JOIN Courier c
            ON sh.courier_id = c.courier_id

         WHERE sh.order_id = ?

         LIMIT 1"
    );

    $s->execute([
        $currentOrderId
    ]);

    $shipment = $s->fetch(PDO::FETCH_ASSOC);

    $shipmentByOrder[$currentOrderId] = $shipment ?: null;
}


// ------------------------------------------------------------
// Presentation
// ------------------------------------------------------------

$pageKey = 'orders/track-order';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/orders/track-order.view.php';

require_once __DIR__ . '/../../includes/footer.php';