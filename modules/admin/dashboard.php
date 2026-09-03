<?php
/**
 * MODULE OWNER: Member 4 (Order Tracking, Reviews, Wishlist, Admin Analytics)
 * Section 3.4 / 4 - Admin exclusive: analytics & reporting
 * TODO (Member 4):
 *  - Add charts (can use plain JS/Canvas since no frameworks allowed)
 *  - Add trending products query (most Order_Item rows)
 *  - Add payment monitoring table
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['admin']);

try {

    /* =====================================================
       1. BASIC STATISTICS
       ===================================================== */

    // Total orders
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM `Order`
    ");
    $totalOrders = (int) $stmt->fetchColumn();


    // Total revenue excluding cancelled orders
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(total_amount), 0)
        FROM `Order`
        WHERE order_status != 'Cancelled'
    ");
    $totalRevenue = (float) $stmt->fetchColumn();


    // Total customers
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM `User`
        WHERE role = 'customer'
    ");
    $totalUsers = (int) $stmt->fetchColumn();


    // Total products
    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM Product
    ");
    $totalProducts = (int) $stmt->fetchColumn();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="admin-dashboard">
    <h1>Admin Dashboard</h1>
    <div class="stats-grid">
        <div class="stat-card"><h2><?= $totalOrders ?></h2><p>Total Orders</p></div>
        <div class="stat-card"><h2>Rs. <?= number_format($totalRevenue, 2) ?></h2><p>Total Revenue</p></div>
        <div class="stat-card"><h2><?= $totalUsers ?></h2><p>Registered Customers</p></div>
    </div>
    <!-- TODO: trending products table, payment status table, pending review moderation queue -->
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
