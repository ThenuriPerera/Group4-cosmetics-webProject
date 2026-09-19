<?php
/**
 * Editor Order Tracking
 *
 * Editor/Admin can update customer order status,
 * shipment information and GPS location.
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role(['editor', 'admin']);

$message = '';
$error = '';

/*
|--------------------------------------------------------------------------
| UPDATE ORDER TRACKING
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_tracking') {

    $orderId = (int)($_POST['order_id'] ?? 0);

    $orderStatus = $_POST['order_status'] ?? 'Pending';
    $deliveryStatus = $_POST['delivery_status'] ?? 'Pending';

    $courierId = (int)($_POST['courier_id'] ?? 0);

    $trackingNumber = trim($_POST['tracking_number'] ?? '');

    $estimateDelivery = $_POST['estimate_delivery'] ?? '';

    /*
     * GPS values
     */
    $latitudeInput = trim($_POST['current_latitude'] ?? '');
    $longitudeInput = trim($_POST['current_longitude'] ?? '');

    $allowedOrderStatuses = [
        'Pending',
        'Processing',
        'Shipped',
        'Delivered',
        'Cancelled'
    ];

    $allowedDeliveryStatuses = [
        'Pending',
        'Shipped',
        'In Transit',
        'Out for Delivery',
        'Delivered'
    ];

    if (
        $orderId <= 0 ||
        !in_array($orderStatus, $allowedOrderStatuses, true) ||
        !in_array($deliveryStatus, $allowedDeliveryStatuses, true)
    ) {

        $error = 'Invalid order tracking information.';

    } else {

        try {

            $pdo->beginTransaction();

            /*
             * Check order exists.
             */
            $stmt = $pdo->prepare("
                SELECT order_id
                FROM `Order`
                WHERE order_id = ?
            ");

            $stmt->execute([$orderId]);

            if (!$stmt->fetch()) {
                throw new Exception('Order not found.');
            }

            /*
             * Update order status.
             */
            $stmt = $pdo->prepare("
                UPDATE `Order`
                SET order_status = ?
                WHERE order_id = ?
            ");

            $stmt->execute([
                $orderStatus,
                $orderId
            ]);

            /*
             * Check existing shipment.
             */
            $stmt = $pdo->prepare("
                SELECT shipment_id
                FROM Shipment
                WHERE order_id = ?
                LIMIT 1
            ");

            $stmt->execute([$orderId]);

            $shipment = $stmt->fetch();

            $courierValue = $courierId > 0
                ? $courierId
                : null;

            $trackingValue = $trackingNumber !== ''
                ? $trackingNumber
                : null;

            $estimateValue = $estimateDelivery !== ''
                ? $estimateDelivery
                : null;

            /*
             * GPS validation
             */
            $latitudeValue = null;
            $longitudeValue = null;

            if ($latitudeInput !== '' && $longitudeInput !== '') {

                if (
                    !is_numeric($latitudeInput) ||
                    !is_numeric($longitudeInput)
                ) {
                    throw new Exception('Invalid GPS coordinates.');
                }

                $latitudeValue = (float)$latitudeInput;
                $longitudeValue = (float)$longitudeInput;

                if (
                    $latitudeValue < -90 ||
                    $latitudeValue > 90 ||
                    $longitudeValue < -180 ||
                    $longitudeValue > 180
                ) {
                    throw new Exception('GPS coordinates are outside the valid range.');
                }
            }

            /*
             * Update existing shipment.
             */
            if ($shipment) {

                if ($latitudeValue !== null && $longitudeValue !== null) {

                    $stmt = $pdo->prepare("
                        UPDATE Shipment
                        SET
                            courier_id = ?,
                            tracking_number = ?,
                            delivery_status = ?,
                            estimate_delivery = ?,
                            current_latitude = ?,
                            current_longitude = ?,
                            location_updated_at = NOW()
                        WHERE shipment_id = ?
                    ");

                    $stmt->execute([
                        $courierValue,
                        $trackingValue,
                        $deliveryStatus,
                        $estimateValue,
                        $latitudeValue,
                        $longitudeValue,
                        $shipment['shipment_id']
                    ]);

                } else {

                    $stmt = $pdo->prepare("
                        UPDATE Shipment
                        SET
                            courier_id = ?,
                            tracking_number = ?,
                            delivery_status = ?,
                            estimate_delivery = ?
                        WHERE shipment_id = ?
                    ");

                    $stmt->execute([
                        $courierValue,
                        $trackingValue,
                        $deliveryStatus,
                        $estimateValue,
                        $shipment['shipment_id']
                    ]);
                }

            }

            /*
             * Create shipment if one does not exist.
             */
            else {

                if ($latitudeValue !== null && $longitudeValue !== null) {

                    $stmt = $pdo->prepare("
                        INSERT INTO Shipment
                        (
                            order_id,
                            courier_id,
                            tracking_number,
                            delivery_status,
                            estimate_delivery,
                            current_latitude,
                            current_longitude,
                            location_updated_at
                        )
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                    ");

                    $stmt->execute([
                        $orderId,
                        $courierValue,
                        $trackingValue,
                        $deliveryStatus,
                        $estimateValue,
                        $latitudeValue,
                        $longitudeValue
                    ]);

                } else {

                    $stmt = $pdo->prepare("
                        INSERT INTO Shipment
                        (
                            order_id,
                            courier_id,
                            tracking_number,
                            delivery_status,
                            estimate_delivery
                        )
                        VALUES (?, ?, ?, ?, ?)
                    ");

                    $stmt->execute([
                        $orderId,
                        $courierValue,
                        $trackingValue,
                        $deliveryStatus,
                        $estimateValue
                    ]);
                }
            }

            /*
             * Save order history.
             */
            $historyStatus = $orderStatus;

            if ($deliveryStatus !== 'Pending') {
                $historyStatus .= ' / ' . $deliveryStatus;
            }

            $stmt = $pdo->prepare("
                INSERT INTO Order_History
                (
                    order_id,
                    order_history_status
                )
                VALUES (?, ?)
            ");

            $stmt->execute([
                $orderId,
                $historyStatus
            ]);

            $pdo->commit();

            $message = "Order #{$orderId} updated successfully.";

        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = 'Could not update the order tracking.';
        }
    }
}

/*
|--------------------------------------------------------------------------
| LOAD COURIERS
|--------------------------------------------------------------------------
*/

$couriers = $pdo->query("
    SELECT
        courier_id,
        company_name,
        contact_number
    FROM Courier
    ORDER BY company_name
")->fetchAll();

/*
|--------------------------------------------------------------------------
| LOAD ORDERS
|--------------------------------------------------------------------------
*/

$orders = $pdo->query("
    SELECT
        o.order_id,
        o.order_status,
        o.order_date,
        o.total_amount,

        u.name AS customer_name,
        u.email AS customer_email,

        s.shipment_id,
        s.courier_id,
        s.tracking_number,
        s.delivery_status,
        s.estimate_delivery,

        s.current_latitude,
        s.current_longitude,
        s.location_updated_at,

        c.company_name AS courier_name

    FROM `Order` o

    INNER JOIN `User` u
        ON u.user_id = o.user_id

    LEFT JOIN Shipment s
        ON s.order_id = o.order_id

    LEFT JOIN Courier c
        ON c.courier_id = s.courier_id

    ORDER BY o.order_date DESC
")->fetchAll();

/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

$pageKey = 'editor/order-tracking';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/editor/order-tracking.view.php';

require_once __DIR__ . '/../../includes/footer.php';