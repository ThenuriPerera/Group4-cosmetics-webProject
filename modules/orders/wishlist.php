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

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="orders-page">
    <h1><?= $orderId ? 'Order Details' : 'My Orders' ?></h1>
    <?php if (isset($_GET['paid'])): ?><p class="success">Payment confirmed — thank you!</p><?php endif; ?>

    <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <p><strong>Order #<?= $order['order_id'] ?></strong> — <?= htmlspecialchars($order['order_status']) ?></p>
            <p>Placed: <?= htmlspecialchars($order['order_date']) ?></p>
            <p>Total: Rs. <?= number_format($order['total_amount'], 2) ?></p>

            <?php $shipment = $shipmentByOrder[$order['order_id']]; ?>
            <?php if ($shipment): ?>
                <div class="shipment-info">
                    <p>Tracking #: <?= htmlspecialchars($shipment['tracking_number'] ?? 'Not yet assigned') ?></p>
                    <p>Courier: <?= htmlspecialchars($shipment['company_name'] ?? '—') ?>
                        <?= $shipment['contact_number'] ? '(' . htmlspecialchars($shipment['contact_number']) . ')' : '' ?></p>
                    <p>Delivery status: <?= htmlspecialchars($shipment['delivery_status']) ?></p>
                    <?php if ($shipment['estimate_delivery']): ?>
                        <p>Estimated delivery: <?= htmlspecialchars($shipment['estimate_delivery']) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h4>Status History</h4>
            <ul class="history-timeline">
                <?php foreach ($historyByOrder[$order['order_id']] as $h): ?>
                    <li><?= htmlspecialchars($h['order_history_status']) ?> — <?= htmlspecialchars($h['time_stamp']) ?></li>
                <?php endforeach; ?>
            </ul>

            <?php if (!$orderId): ?><a href="?order_id=<?= $order['order_id'] ?>">View Details</a><?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?><p>No orders yet.</p><?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>  