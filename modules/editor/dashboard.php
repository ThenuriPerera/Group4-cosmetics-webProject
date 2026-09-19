<?php
/**
 * Luminé Glow - Editor Dashboard
 *
 * Main navigation for Editor/Admin staff.
 *
 * Product management is handled by:
 * /modules/products/manage.php
 *
 * Order tracking is handled by:
 * /modules/editor/order-tracking.php
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role(['editor', 'admin']);

$pageKey = 'editor/dashboard';


/*
|--------------------------------------------------------------------------
| SMART ORDER OVERVIEW
|--------------------------------------------------------------------------
*/

/*
 * Total orders
 */
$stmt = $pdo->query("
    SELECT COUNT(*) 
    FROM `Order`
");

$totalOrders = (int) $stmt->fetchColumn();


/*
 * Orders that have not received shipment tracking yet
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM `Order` o
    LEFT JOIN Shipment sh
        ON sh.order_id = o.order_id
    WHERE sh.shipment_id IS NULL
");

$ordersWithoutShipment = (int) $stmt->fetchColumn();


/*
 * Pending shipments
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status = 'Pending'
");

$pendingOrders = (int) $stmt->fetchColumn();


/*
 * Shipped orders
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status = 'Shipped'
");

$shippedOrders = (int) $stmt->fetchColumn();


/*
 * In Transit
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status = 'In Transit'
");

$inTransitOrders = (int) $stmt->fetchColumn();


/*
 * Out for Delivery
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status = 'Out for Delivery'
");

$outForDeliveryOrders = (int) $stmt->fetchColumn();


/*
 * Delivered
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status = 'Delivered'
");

$deliveredOrders = (int) $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| SMART ATTENTION COUNTS
|--------------------------------------------------------------------------
*/

/*
 * Shipments without a courier
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE courier_id IS NULL
    AND delivery_status <> 'Delivered'
");

$ordersWithoutCourier = (int) $stmt->fetchColumn();


/*
 * Shipments without GPS
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status IN (
        'Shipped',
        'In Transit',
        'Out for Delivery'
    )
    AND (
        current_latitude IS NULL
        OR current_longitude IS NULL
    )
");

$gpsNotStarted = (int) $stmt->fetchColumn();


/*
 * GPS not updated for more than 30 minutes
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status IN (
        'Shipped',
        'In Transit',
        'Out for Delivery'
    )
    AND current_latitude IS NOT NULL
    AND current_longitude IS NOT NULL
    AND (
        location_updated_at IS NULL
        OR location_updated_at < DATE_SUB(
            NOW(),
            INTERVAL 30 MINUTE
        )
    )
");

$gpsOffline = (int) $stmt->fetchColumn();


/*
 * Delivery dates that have passed
 */
$stmt = $pdo->query("
    SELECT COUNT(*)
    FROM Shipment
    WHERE delivery_status <> 'Delivered'
    AND estimate_delivery IS NOT NULL
    AND estimate_delivery < CURDATE()
");

$overdueOrders = (int) $stmt->fetchColumn();


/*
|--------------------------------------------------------------------------
| LOAD DASHBOARD
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/editor/dashboard.view.php';

require_once __DIR__ . '/../../includes/footer.php';