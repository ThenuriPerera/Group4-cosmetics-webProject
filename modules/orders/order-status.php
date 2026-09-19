<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

$userId = (int) current_user()['user_id'];
$orderId = (int)($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid order.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Check that this order belongs to the logged-in customer
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT order_id
    FROM `Order`
    WHERE order_id = ?
    AND user_id = ?
    LIMIT 1
");

$stmt->execute([
    $orderId,
    $userId
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo json_encode([
        'success' => false,
        'message' => 'Order not found.'
    ]);
    exit;
}


/*
|--------------------------------------------------------------------------
| Get latest shipment information
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        sh.delivery_status,
        sh.tracking_number,
        sh.estimate_delivery,

        sh.current_latitude,
        sh.current_longitude,
        sh.location_updated_at,

        c.company_name,
        c.contact_number

    FROM Shipment sh

    LEFT JOIN Courier c
        ON sh.courier_id = c.courier_id

    WHERE sh.order_id = ?

    ORDER BY sh.shipment_id DESC

    LIMIT 1
");

$stmt->execute([
    $orderId
]);

$shipment = $stmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Return live tracking information
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success' => true,

    'shipment' => $shipment ?: null
]);

exit;