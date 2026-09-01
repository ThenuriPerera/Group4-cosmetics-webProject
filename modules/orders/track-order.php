<?php
/**
 * MODULE OWNER: Member 4 (Order Tracking, Reviews, Wishlist, Admin Analytics)
 * Section 5.6 - Order & Shipment Tracking
 * TODO (Member 4):
 *  - Show full Order_History timeline (all status changes, not just latest)
 *  - Pull in Shipment + Courier info once assigned by admin
 *  - List all past orders if no order_id given (order history view)
 */
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

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="orders-page">
    <h1>My Orders</h1>
    <?php foreach ($orders as $order): ?>
        <div class="order-card">
            <p>Order #<?= $order['order_id'] ?> — <?= htmlspecialchars($order['order_status']) ?></p>
            <p>Placed: <?= htmlspecialchars($order['order_date']) ?></p>
            <p>Total: Rs. <?= number_format($order['total_amount'], 2) ?></p>
            <!-- TODO: render Order_History timeline + Shipment tracking number here -->
        </div>
    <?php endforeach; ?>
    <?php if (empty($orders)): ?>
        <p>No orders yet.</p>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
