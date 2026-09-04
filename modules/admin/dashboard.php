<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['admin']);

$totalOrders = $pdo->query("SELECT COUNT(*) FROM `Order`")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total_amount),0) FROM `Order` WHERE order_status != 'Cancelled'")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM User WHERE role = 'customer'")->fetchColumn();
$pendingReviewCount = $pdo->query("SELECT COUNT(*) FROM Review WHERE status = 'Pending'")->fetchColumn();

// Trending products: most units sold across all orders
$trending = $pdo->query(
    "SELECT p.product_name, SUM(oi.quantity) AS units_sold
     FROM Order_Item oi JOIN Product p ON oi.product_id = p.product_id
     GROUP BY oi.product_id ORDER BY units_sold DESC LIMIT 5"
)->fetchAll();

// Recent payments for monitoring
$payments = $pdo->query(
    "SELECT pay.*, o.user_id FROM Payment pay JOIN `Order` o ON pay.order_id = o.order_id
     ORDER BY pay.payment_date DESC LIMIT 10"
)->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <div class="stats-grid">
        <div class="stat-card"><h2><?= $totalOrders ?></h2><p>Total Orders</p></div>
        <div class="stat-card"><h2>Rs. <?= number_format($totalRevenue, 2) ?></h2><p>Total Revenue</p></div>
        <div class="stat-card"><h2><?= $totalUsers ?></h2><p>Registered Customers</p></div>
        <div class="stat-card"><h2><?= $pendingReviewCount ?></h2><p>Reviews Awaiting Moderation</p></div>
    </div>

    <p><a href="/modules/admin/review-moderation.php">Go to Review Moderation →</a></p>
    <p><a href="/modules/products/manage.php">Manage Products →</a></p>

    <h2>Trending Products</h2>
    <table class="admin-table">
        <thead><tr><th>Product</th><th>Units Sold</th></tr></thead>
        <tbody>
        <?php foreach ($trending as $t): ?>
            <tr><td><?= htmlspecialchars($t['product_name']) ?></td><td><?= $t['units_sold'] ?></td></tr>
        <?php endforeach; ?>
        <?php if (empty($trending)): ?><tr><td colspan="2">No sales yet.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <h2>Recent Payments</h2>
    <table class="admin-table">
        <thead><tr><th>Order</th><th>Method</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($payments as $p): ?>
            <tr>
                <td>#<?= $p['order_id'] ?></td>
                <td><?= htmlspecialchars($p['method']) ?></td>
                <td>Rs. <?= number_format($p['amount'], 2) ?></td>
                <td><?= htmlspecialchars($p['status']) ?></td>
                <td><?= htmlspecialchars($p['payment_date']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($payments)): ?><tr><td colspan="5">No payments yet.</td></tr><?php endif; ?>
        </tbody>
    </table>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?> 