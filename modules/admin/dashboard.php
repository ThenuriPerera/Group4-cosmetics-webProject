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

    /* =====================================================
       2. ORDER STATUS STATISTICS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            order_status,
            COUNT(*) AS total
        FROM `Order`
        GROUP BY order_status
    ");

    $orderStatusRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $orderStatus = [
        'Pending' => 0,
        'Processing' => 0,
        'Shipped' => 0,
        'Delivered' => 0,
        'Cancelled' => 0
    ];

    foreach ($orderStatusRows as $row) {

        if (isset($orderStatus[$row['order_status']])) {
            $orderStatus[$row['order_status']]
                = (int) $row['total'];
        }
    }
/* =====================================================
       3. PAYMENT STATISTICS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            status,
            COUNT(*) AS total
        FROM Payment
        GROUP BY status
    ");

    $paymentStatusRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $paymentStatus = [
        'Pending' => 0,
        'Completed' => 0,
        'Failed' => 0
    ];

    foreach ($paymentStatusRows as $row) {

        if (isset($paymentStatus[$row['status']])) {
            $paymentStatus[$row['status']]
                = (int) $row['total'];
        }
    }

/* =====================================================
       4. REVIEW STATISTICS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            status,
            COUNT(*) AS total
        FROM Review
        GROUP BY status
    ");

    $reviewStatusRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reviewStatus = [
        'Pending' => 0,
        'Approved' => 0,
        'Rejected' => 0
    ];

    foreach ($reviewStatusRows as $row) {

        if (isset($reviewStatus[$row['status']])) {
            $reviewStatus[$row['status']]
                = (int) $row['total'];
        }
    }

/* =====================================================
       5. WISHLIST STATISTICS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM Wishlist
    ");

    $totalWishlistItems = (int) $stmt->fetchColumn();
/* =====================================================
       6. TRENDING PRODUCTS BY SALES
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            p.product_id,
            p.product_name,
            SUM(oi.quantity) AS total_sold
        FROM Order_Item oi
        INNER JOIN Product p
            ON p.product_id = oi.product_id
        INNER JOIN `Order` o
            ON o.order_id = oi.order_id
        WHERE o.order_status != 'Cancelled'
        GROUP BY
            p.product_id,
            p.product_name
        ORDER BY total_sold DESC
        LIMIT 5
    ");

    $trendingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
  * =====================================================
       7. MOST WISHLISTED PRODUCTS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            p.product_id,
            p.product_name,
            COUNT(w.wishlist_id) AS wishlist_count
        FROM Wishlist w
        INNER JOIN Product p
            ON p.product_id = w.product_id
        GROUP BY
            p.product_id,
            p.product_name
        ORDER BY wishlist_count DESC
        LIMIT 5
    ");

    $wishlistProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* =====================================================
       8. AVERAGE PRODUCT RATINGS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            p.product_id,
            p.product_name,
            ROUND(AVG(r.rating), 1) AS average_rating,
            COUNT(r.review_id) AS review_count
        FROM Review r
        INNER JOIN Product p
            ON p.product_id = r.product_id
        WHERE r.status = 'Approved'
        GROUP BY
            p.product_id,
            p.product_name
        ORDER BY average_rating DESC
        LIMIT 5
    ");

    $topRatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /* =====================================================
       9. PAYMENT MONITORING
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            p.payment_id,
            p.order_id,
            p.method,
            p.amount,
            p.status,
            p.payment_date
        FROM Payment p
        ORDER BY p.payment_date DESC
        LIMIT 10
    ");

    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* =====================================================
       10. RECENT ORDERS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            o.order_id,
            u.name AS customer_name,
            o.total_amount,
            o.order_status,
            o.order_date
        FROM `Order` o
        INNER JOIN `User` u
            ON u.user_id = o.user_id
        ORDER BY o.order_date DESC
        LIMIT 10
    ");

    $recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);
/* =====================================================
       11. PENDING REVIEWS
       ===================================================== */

    $stmt = $pdo->query("
        SELECT
            r.review_id,
            u.name AS customer_name,
            p.product_name,
            r.rating,
            r.comment,
            r.rating_date
        FROM Review r
        INNER JOIN `User` u
            ON u.user_id = r.user_id
        INNER JOIN Product p
            ON p.product_id = r.product_id
        WHERE r.status = 'Pending'
        ORDER BY r.rating_date DESC
        LIMIT 10
    ");

    $pendingReviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {

    error_log($e->getMessage());

    $totalOrders = 0;
    $totalRevenue = 0;
    $totalUsers = 0;
    $totalProducts = 0;
    $totalWishlistItems = 0;

    $orderStatus = [
        'Pending' => 0,
        'Processing' => 0,
        'Shipped' => 0,
        'Delivered' => 0,
        'Cancelled' => 0
    ];

    $paymentStatus = [
        'Pending' => 0,
        'Completed' => 0,
        'Failed' => 0
    ];

    $reviewStatus = [
        'Pending' => 0,
        'Approved' => 0,
        'Rejected' => 0
    ];

    $trendingProducts = [];
    $wishlistProducts = [];
    $topRatedProducts = [];
    $payments = [];
    $recentOrders = [];
    $pendingReviews = [];

    $databaseError = true;
}


require_once __DIR__ . '/../../includes/header.php';
?>
<section class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <?php if (!empty($databaseError)): ?>

        <div class="alert alert-danger">
            Unable to load dashboard data.
            Please try again later.
        </div>

    <?php endif; ?>

    <<!-- =================================================
         MAIN STATISTICS
         ================================================= -->

    <div class="stats-grid">

        <div class="stat-card">
            <h2><?= number_format($totalOrders) ?></h2>
            <p>Total Orders</p>
        </div>


        <div class="stat-card">
            <h2>
                Rs. <?= number_format($totalRevenue, 2) ?>
            </h2>
            <p>Total Revenue</p>
        </div>


        <div class="stat-card">
            <h2><?= number_format($totalUsers) ?></h2>
            <p>Customers</p>
        </div>


        <div class="stat-card">
            <h2><?= number_format($totalProducts) ?></h2>
            <p>Products</p>
        </div>


        <div class="stat-card">
            <h2><?= number_format($totalWishlistItems) ?></h2>
            <p>Wishlist Items</p>
        </div>

    </div>
    <!-- =================================================
         ORDER STATUS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Order Status</h2>

        <div class="stats-grid">

            <?php foreach ($orderStatus as $status => $count): ?>

                <div class="stat-card">

                    <h2><?= number_format($count) ?></h2>

                    <p>
                        <?= htmlspecialchars($status) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </section>

<!-- =================================================
         PAYMENT STATUS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Payment Status</h2>

        <div class="stats-grid">

            <?php foreach ($paymentStatus as $status => $count): ?>

                <div class="stat-card">

                    <h2><?= number_format($count) ?></h2>

                    <p>
                        <?= htmlspecialchars($status) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </section>
<!-- =================================================
         REVIEW STATUS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Review Statistics</h2>

        <div class="stats-grid">

            <?php foreach ($reviewStatus as $status => $count): ?>

                <div class="stat-card">

                    <h2><?= number_format($count) ?></h2>

                    <p>
                        <?= htmlspecialchars($status) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        </div>

    </section>

<!-- =================================================
         TRENDING PRODUCTS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Trending Products</h2>

        <?php if (!empty($trendingProducts)): ?>

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>Product</th>
                        <th>Total Sold</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($trendingProducts as $product): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    $product['product_name']
                                ) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    $product['total_sold']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No sales data available.</p>

        <?php endif; ?>

    </section>
<!-- =================================================
         MOST WISHLISTED PRODUCTS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Most Wishlisted Products</h2>

        <?php if (!empty($wishlistProducts)): ?>

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>Product</th>
                        <th>Wishlist Count</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($wishlistProducts as $product): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    $product['product_name']
                                ) ?>
                            </td>

                            <td>
                                <?= number_format(
                                    $product['wishlist_count']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No wishlist data available.</p>

        <?php endif; ?>

    </section>
    <!-- =================================================
         TOP RATED PRODUCTS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Top Rated Products</h2>

        <?php if (!empty($topRatedProducts)): ?>

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Reviews</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($topRatedProducts as $product): ?>

                        <tr>

                            <td>
                                <?= htmlspecialchars(
                                    $product['product_name']
                                ) ?>
                            </td>

                            <td>
                                ⭐ <?= htmlspecialchars(
                                    $product['average_rating']
                                ) ?>/5
                            </td>

                            <td>
                                <?= number_format(
                                    $product['review_count']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No approved reviews available.</p>

        <?php endif; ?>

    </section>

<!-- =================================================
         RECENT ORDERS
         ================================================= -->

    <section class="dashboard-section">

        <h2>Recent Orders</h2>

        <?php if (!empty($recentOrders)): ?>

            <table class="admin-table">

                <thead>

                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($recentOrders as $order): ?>

                        <tr>

                            <td>
                                #<?= htmlspecialchars(
                                    $order['order_id']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $order['customer_name']
                                ) ?>
                            </td>

                            <td>
                                Rs.
                                <?= number_format(
                                    (float)$order['total_amount'],
                                    2
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $order['order_status']
                                ) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $order['order_date']
                                ) ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p>No orders found.</p>

        <?php endif; ?>

    </section>





<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
