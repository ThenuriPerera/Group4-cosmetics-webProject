<?php
/**
 * Lumine Glow SmartTrack
 *
 * Editor-only order tracking controller.
 *
 * Features:
 * - Update order status
 * - Update delivery status
 * - Select courier
 * - Set tracking number
 * - Set estimated delivery
 * - Start browser GPS tracking
 * - Receive automatic GPS updates
 * - Save courier GPS coordinates
 * - Save GPS update time
 * - Return GPS status as JSON
 * - Maintain order history
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role(['editor']);


/*
|--------------------------------------------------------------------------
| AJAX: UPDATE GPS
|--------------------------------------------------------------------------
|
| JavaScript sends:
|
| action      = update_gps
| order_id    = order ID
| latitude    = browser latitude
| longitude   = browser longitude
|
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'update_gps'
) {
    header('Content-Type: application/json; charset=utf-8');

    $orderId = (int) ($_POST['order_id'] ?? 0);

    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;


    /*
    |--------------------------------------------------------------------------
    | Validate order ID
    |--------------------------------------------------------------------------
    */

    if ($orderId <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid order ID.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Validate GPS values
    |--------------------------------------------------------------------------
    */

    if (
        $latitude === null ||
        $longitude === null ||
        !is_numeric($latitude) ||
        !is_numeric($longitude)
    ) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid GPS coordinates.'
        ]);
        exit;
    }

    $latitude = (float) $latitude;
    $longitude = (float) $longitude;


    if ($latitude < -90 || $latitude > 90) {
        echo json_encode([
            'success' => false,
            'message' => 'Latitude must be between -90 and 90.'
        ]);
        exit;
    }


    if ($longitude < -180 || $longitude > 180) {
        echo json_encode([
            'success' => false,
            'message' => 'Longitude must be between -180 and 180.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Check order exists
    |--------------------------------------------------------------------------
    */

    $orderCheck = $pdo->prepare(
        "SELECT
            order_id,
            order_status
         FROM `Order`
         WHERE order_id = ?
         LIMIT 1"
    );

    $orderCheck->execute([$orderId]);

    $order = $orderCheck->fetch();


    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => 'Order not found.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Do not update GPS for cancelled orders
    |--------------------------------------------------------------------------
    */

    if ($order['order_status'] === 'Cancelled') {
        echo json_encode([
            'success' => false,
            'message' => 'GPS tracking cannot be updated for a cancelled order.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Find shipment
    |--------------------------------------------------------------------------
    */

    $shipmentCheck = $pdo->prepare(
        "SELECT
            shipment_id,
            courier_id,
            tracking_number,
            delivery_status,
            estimate_delivery
         FROM Shipment
         WHERE order_id = ?
         LIMIT 1"
    );

    $shipmentCheck->execute([$orderId]);

    $shipment = $shipmentCheck->fetch();


    /*
    |--------------------------------------------------------------------------
    | Shipment must exist
    |--------------------------------------------------------------------------
    |
    | The editor should first save courier/tracking information.
    |
    */

    if (!$shipment) {
        echo json_encode([
            'success' => false,
            'message' => 'Please save the courier and tracking information before starting GPS tracking.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Do not track delivered orders
    |--------------------------------------------------------------------------
    */

    if ($shipment['delivery_status'] === 'Delivered') {
        echo json_encode([
            'success' => false,
            'message' => 'GPS tracking is not required for a delivered order.'
        ]);
        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Save GPS
    |--------------------------------------------------------------------------
    */

    $updateGps = $pdo->prepare(
        "UPDATE Shipment
         SET
            current_latitude = ?,
            current_longitude = ?,
            location_updated_at = NOW()
         WHERE shipment_id = ?"
    );

    $updateGps->execute([
        $latitude,
        $longitude,
        $shipment['shipment_id']
    ]);


    /*
    |--------------------------------------------------------------------------
    | Get exact database timestamp
    |--------------------------------------------------------------------------
    */

    $timeStmt = $pdo->prepare(
        "SELECT location_updated_at
         FROM Shipment
         WHERE shipment_id = ?
         LIMIT 1"
    );

    $timeStmt->execute([
        $shipment['shipment_id']
    ]);

    $updatedAt = $timeStmt->fetchColumn();


    /*
    |--------------------------------------------------------------------------
    | Return response
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        'success' => true,
        'message' => 'GPS location updated successfully.',
        'order_id' => $orderId,
        'shipment_id' => (int) $shipment['shipment_id'],
        'latitude' => $latitude,
        'longitude' => $longitude,
        'updated_at' => $updatedAt
    ]);

    exit;
}



/*
|--------------------------------------------------------------------------
| UPDATE TRACKING INFORMATION
|--------------------------------------------------------------------------
*/

$message = '';
$error = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'update_tracking'
) {

    $orderId = (int) ($_POST['order_id'] ?? 0);

    $orderStatus = trim(
        $_POST['order_status'] ?? 'Pending'
    );

    $deliveryStatus = trim(
        $_POST['delivery_status'] ?? 'Pending'
    );

    $courierId = (int) (
        $_POST['courier_id'] ?? 0
    );

    $trackingNumber = trim(
        $_POST['tracking_number'] ?? ''
    );

    $estimateDelivery = trim(
        $_POST['estimate_delivery'] ?? ''
    );

    $latitude = trim(
        $_POST['current_latitude'] ?? ''
    );

    $longitude = trim(
        $_POST['current_longitude'] ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | Allowed statuses
    |--------------------------------------------------------------------------
    */

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


    /*
    |--------------------------------------------------------------------------
    | Basic validation
    |--------------------------------------------------------------------------
    */

    if ($orderId <= 0) {

        $error = 'Invalid order ID.';

    } elseif (
        !in_array(
            $orderStatus,
            $allowedOrderStatuses,
            true
        )
    ) {

        $error = 'Invalid order status.';

    } elseif (
        !in_array(
            $deliveryStatus,
            $allowedDeliveryStatuses,
            true
        )
    ) {

        $error = 'Invalid delivery status.';

    } elseif ($courierId <= 0) {

        $error = 'Please select a courier.';

    } elseif ($trackingNumber === '') {

        $error = 'Tracking number is required.';

    } elseif ($estimateDelivery === '') {

        $error = 'Estimated delivery date is required.';
    }


    /*
    |--------------------------------------------------------------------------
    | GPS validation
    |--------------------------------------------------------------------------
    */

    $latitudeValue = null;
    $longitudeValue = null;

    if ($error === '' && $latitude !== '') {

        if (!is_numeric($latitude)) {

            $error = 'Latitude must be a valid number.';

        } else {

            $latitudeValue = (float) $latitude;

            if (
                $latitudeValue < -90 ||
                $latitudeValue > 90
            ) {

                $error = 'Latitude must be between -90 and 90.';
            }
        }
    }


    if ($error === '' && $longitude !== '') {

        if (!is_numeric($longitude)) {

            $error = 'Longitude must be a valid number.';

        } else {

            $longitudeValue = (float) $longitude;

            if (
                $longitudeValue < -180 ||
                $longitudeValue > 180
            ) {

                $error = 'Longitude must be between -180 and 180.';
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GPS must have both coordinates
    |--------------------------------------------------------------------------
    */

    if (
        $error === '' &&
        (
            ($latitude !== '' && $longitude === '') ||
            ($latitude === '' && $longitude !== '')
        )
    ) {

        $error = 'Both latitude and longitude are required.';
    }


    /*
    |--------------------------------------------------------------------------
    | Check order
    |--------------------------------------------------------------------------
    */

    $existingOrder = null;

    if ($error === '') {

        $orderCheck = $pdo->prepare(
            "SELECT
                order_id,
                order_status
             FROM `Order`
             WHERE order_id = ?
             LIMIT 1"
        );

        $orderCheck->execute([
            $orderId
        ]);

        $existingOrder = $orderCheck->fetch();


        if (!$existingOrder) {
            $error = 'Order not found.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Check courier
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $courierCheck = $pdo->prepare(
            "SELECT
                courier_id,
                company_name
             FROM Courier
             WHERE courier_id = ?
             LIMIT 1"
        );

        $courierCheck->execute([
            $courierId
        ]);

        $courier = $courierCheck->fetch();


        if (!$courier) {
            $error = 'Selected courier does not exist.';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Save tracking
    |--------------------------------------------------------------------------
    */

    if ($error === '') {

        $shipmentCheck = $pdo->prepare(
            "SELECT *
             FROM Shipment
             WHERE order_id = ?
             LIMIT 1"
        );

        $shipmentCheck->execute([
            $orderId
        ]);

        $existingShipment = $shipmentCheck->fetch();


        try {

            $pdo->beginTransaction();


            /*
            |--------------------------------------------------------------------------
            | Update order
            |--------------------------------------------------------------------------
            */

            $updateOrder = $pdo->prepare(
                "UPDATE `Order`
                 SET order_status = ?
                 WHERE order_id = ?"
            );

            $updateOrder->execute([
                $orderStatus,
                $orderId
            ]);


            /*
            |--------------------------------------------------------------------------
            | Create shipment
            |--------------------------------------------------------------------------
            */

            if (!$existingShipment) {

                if (
                    $latitudeValue !== null &&
                    $longitudeValue !== null
                ) {

                    $insertShipment = $pdo->prepare(
                        "INSERT INTO Shipment
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
                        VALUES
                        (
                            ?, ?, ?, ?, ?, ?, ?, NOW()
                        )"
                    );

                    $insertShipment->execute([
                        $orderId,
                        $courierId,
                        $trackingNumber,
                        $deliveryStatus,
                        $estimateDelivery,
                        $latitudeValue,
                        $longitudeValue
                    ]);

                } else {

                    $insertShipment = $pdo->prepare(
                        "INSERT INTO Shipment
                        (
                            order_id,
                            courier_id,
                            tracking_number,
                            delivery_status,
                            estimate_delivery
                        )
                        VALUES
                        (
                            ?, ?, ?, ?, ?
                        )"
                    );

                    $insertShipment->execute([
                        $orderId,
                        $courierId,
                        $trackingNumber,
                        $deliveryStatus,
                        $estimateDelivery
                    ]);
                }


            } else {


                /*
                |--------------------------------------------------------------------------
                | Update existing shipment
                |--------------------------------------------------------------------------
                */

                if (
                    $latitudeValue !== null &&
                    $longitudeValue !== null
                ) {

                    $updateShipment = $pdo->prepare(
                        "UPDATE Shipment
                         SET
                            courier_id = ?,
                            tracking_number = ?,
                            delivery_status = ?,
                            estimate_delivery = ?,
                            current_latitude = ?,
                            current_longitude = ?,
                            location_updated_at = NOW()
                         WHERE shipment_id = ?"
                    );

                    $updateShipment->execute([
                        $courierId,
                        $trackingNumber,
                        $deliveryStatus,
                        $estimateDelivery,
                        $latitudeValue,
                        $longitudeValue,
                        $existingShipment['shipment_id']
                    ]);

                } else {

                    $updateShipment = $pdo->prepare(
                        "UPDATE Shipment
                         SET
                            courier_id = ?,
                            tracking_number = ?,
                            delivery_status = ?,
                            estimate_delivery = ?
                         WHERE shipment_id = ?"
                    );

                    $updateShipment->execute([
                        $courierId,
                        $trackingNumber,
                        $deliveryStatus,
                        $estimateDelivery,
                        $existingShipment['shipment_id']
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Record order history
            |--------------------------------------------------------------------------
            */

            $oldOrderStatus =
                $existingOrder['order_status'];

            $oldDeliveryStatus =
                $existingShipment['delivery_status']
                ?? null;


            if (
                $oldOrderStatus !== $orderStatus ||
                $oldDeliveryStatus !== $deliveryStatus
            ) {

                /*
                 * If order status changed, record it.
                 * Otherwise record delivery status.
                 */

                if (
                    $oldOrderStatus !== $orderStatus
                ) {

                    $historyText = $orderStatus;

                } else {

                    $historyText = $deliveryStatus;
                }


                $historyStmt = $pdo->prepare(
                    "INSERT INTO Order_History
                    (
                        order_id,
                        order_history_status
                    )
                    VALUES (?, ?)"
                );

                $historyStmt->execute([
                    $orderId,
                    $historyText
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            $pdo->commit();

            $message =
                'SmartTrack information updated successfully.';


        } catch (Throwable $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error =
                'Could not update tracking information: ' .
                $e->getMessage();
        }
    }
}



/*
|--------------------------------------------------------------------------
| LOAD COURIERS
|--------------------------------------------------------------------------
*/

$courierStmt = $pdo->query(
    "SELECT
        courier_id,
        company_name,
        contact_number
     FROM Courier
     ORDER BY company_name ASC"
);

$couriers = $courierStmt->fetchAll();



/*
|--------------------------------------------------------------------------
| LOAD ORDERS
|--------------------------------------------------------------------------
*/

$orderStmt = $pdo->query(
    "SELECT
        o.order_id,
        o.user_id,
        o.total_amount,
        o.order_status,
        o.order_date,

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

        c.company_name,
        c.contact_number

     FROM `Order` AS o

     INNER JOIN `User` AS u
        ON u.user_id = o.user_id

     LEFT JOIN Shipment AS s
        ON s.order_id = o.order_id

     LEFT JOIN Courier AS c
        ON c.courier_id = s.courier_id

     ORDER BY o.order_date DESC"
);

$orders = $orderStmt->fetchAll();



/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageKey = 'editor/order-tracking';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/editor/order-tracking.view.php';

require_once __DIR__ . '/../../includes/footer.php';