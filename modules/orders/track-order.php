<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$userId = current_user()['user_id'];
$orderId = $_GET['order_id'] ?? null;

if ($orderId) {
    $stmt = $pdo->prepare(
        "SELECT *
         FROM `Order`
         WHERE order_id = ?
           AND user_id = ?"
    );

    $stmt->execute([
        $orderId,
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

/*
|--------------------------------------------------------------------------
| Tracking history and shipment information
|--------------------------------------------------------------------------
*/

$historyByOrder = [];
$shipmentByOrder = [];

foreach ($orders as $order) {

    $orderNumber = $order['order_id'];

    /*
    |--------------------------------------------------------------------------
    | Order history
    |--------------------------------------------------------------------------
    */

    $historyStmt = $pdo->prepare(
        "SELECT *
         FROM Order_History
         WHERE order_id = ?
         ORDER BY time_stamp ASC"
    );

    $historyStmt->execute([
        $orderNumber
    ]);

    $historyByOrder[$orderNumber] = $historyStmt->fetchAll();

    /*
    |--------------------------------------------------------------------------
    | Shipment + courier
    |--------------------------------------------------------------------------
    */

    $shipmentStmt = $pdo->prepare(
        "SELECT
            sh.*,
            c.company_name,
            c.contact_number
         FROM Shipment AS sh
         LEFT JOIN Courier AS c
            ON sh.courier_id = c.courier_id
         WHERE sh.order_id = ?
         LIMIT 1"
    );

    $shipmentStmt->execute([
        $orderNumber
    ]);

    $shipmentByOrder[$orderNumber] = $shipmentStmt->fetch();
}

/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageKey = 'orders/track-order';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/orders/track-order.view.php';

require_once __DIR__ . '/../../includes/footer.php';