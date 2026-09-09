<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="admin-dashboard">

    <div class="stats-grid">
        <div class="stat-card"><h2><?= $totalOrders ?></h2><p>Total Orders</p></div>
        <div class="stat-card"><h2>Rs. <?= number_format($totalRevenue, 2) ?></h2><p>Total Revenue</p></div>
        <div class="stat-card"><h2><?= $totalUsers ?></h2><p>Registered Customers</p></div>
        <div class="stat-card"><h2><?= $pendingReviewCount ?></h2><p>Reviews Awaiting Moderation</p></div>
    </div>

    <p><a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/admin/review-moderation.php">Go to Review Moderation →</a></p>
    <p><a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/products/manage.php">Manage Products →</a></p>

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
