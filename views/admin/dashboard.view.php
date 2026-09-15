<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}

$base = htmlspecialchars(
    LG_BASE_PATH,
    ENT_QUOTES,
    'UTF-8'
);

$totalOrders = (int) ($totalOrders ?? 0);
$totalRevenue = (float) ($totalRevenue ?? 0);
$totalUsers = (int) ($totalUsers ?? 0);
$pendingReviewCount = (int) ($pendingReviewCount ?? 0);

$orderStatuses = $orderStatuses ?? [
    'Pending' => 0,
    'Processing' => 0,
    'Shipped' => 0,
    'Delivered' => 0,
    'Cancelled' => 0
];

$paymentStatuses = $paymentStatuses ?? [
    'Pending' => 0,
    'Completed' => 0,
    'Failed' => 0
];

$trending = $trending ?? [];
$recentOrders = $recentOrders ?? [];
$payments = $payments ?? [];
?>

<section class="admin-dashboard-page">

    <div class="admin-welcome">
        <div>
            <p class="admin-overline">LUMINÉ GLOW ADMINISTRATION</p>
            <h2>Store overview</h2>
            <p class="admin-description">
                Manage your beauty store, monitor performance and keep
                everything running beautifully.
            </p>
        </div>

        <div class="admin-date">
            <?= date('l, d F Y') ?>
        </div>
    </div>

    <div class="admin-quick-actions">
        <a href="<?= $base ?>/modules/products/manage.php">
            <span>＋</span>
            Add product
        </a>

        <a href="<?= $base ?>/modules/admin/review-moderation.php">
            <span>☆</span>
            Review moderation
        </a>

        <a href="<?= $base ?>/modules/products/index.php">
            <span>↗</span>
            View storefront
        </a>
    </div>

    <div class="admin-metrics">

        <article class="admin-metric-card">
            <div class="metric-icon rose">₨</div>
            <div>
                <p>Total revenue</p>
                <h3>Rs. <?= number_format($totalRevenue, 2) ?></h3>
                <small>From completed orders</small>
            </div>
        </article>

        <article class="admin-metric-card">
            <div class="metric-icon lavender">▣</div>
            <div>
                <p>Total orders</p>
                <h3><?= number_format($totalOrders) ?></h3>
                <small>All customer orders</small>
            </div>
        </article>

        <article class="admin-metric-card">
            <div class="metric-icon peach">♙</div>
            <div>
                <p>Customers</p>
                <h3><?= number_format($totalUsers) ?></h3>
                <small>Registered customers</small>
            </div>
        </article>

        <article class="admin-metric-card">
            <div class="metric-icon cream">☆</div>
            <div>
                <p>Pending reviews</p>
                <h3><?= number_format($pendingReviewCount) ?></h3>
                <small>Waiting for approval</small>
            </div>
        </article>

    </div>

    <div class="admin-main-grid">

        <article class="admin-card admin-sales-card">

            <div class="admin-card-heading">
                <div>
                    <p class="admin-card-label">PERFORMANCE</p>
                    <h3>Sales overview</h3>
                    <p>Weekly sales activity</p>
                </div>

                <select class="admin-period-select">
                    <option>This week</option>
                    <option>This month</option>
                    <option>This year</option>
                </select>
            </div>

            <div class="sales-summary">
                <strong>Rs. <?= number_format($totalRevenue, 2) ?></strong>
                <span class="sales-growth">↑ 8.2%</span>
                <small>compared with last period</small>
            </div>

            <div class="admin-sales-chart">

                <div class="sales-axis">
                    <span>80k</span>
                    <span>60k</span>
                    <span>40k</span>
                    <span>20k</span>
                    <span>0</span>
                </div>

                <div class="sales-chart-area">

                    <div class="sales-lines">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </div>

                    <div class="sales-bars">
                        <div style="height: 38%">
                            <span></span>
                            <small>Mon</small>
                        </div>

                        <div style="height: 52%">
                            <span></span>
                            <small>Tue</small>
                        </div>

                        <div style="height: 45%">
                            <span></span>
                            <small>Wed</small>
                        </div>

                        <div style="height: 68%">
                            <span></span>
                            <small>Thu</small>
                        </div>

                        <div style="height: 57%">
                            <span></span>
                            <small>Fri</small>
                        </div>

                        <div style="height: 82%">
                            <span></span>
                            <small>Sat</small>
                        </div>

                        <div style="height: 72%">
                            <span></span>
                            <small>Sun</small>
                        </div>
                    </div>

                </div>

            </div>

        </article>

        <article class="admin-card order-status-card">

            <div class="admin-card-heading">
                <div>
                    <p class="admin-card-label">ORDERS</p>
                    <h3>Order status</h3>
                    <p>Current order distribution</p>
                </div>
            </div>

            <div class="order-status-content">

                <div class="order-donut">
                    <div>
                        <strong><?= number_format($totalOrders) ?></strong>
                        <span>Total orders</span>
                    </div>
                </div>

                <div class="order-legend">
                    <p>
                        <i class="status-pending"></i>
                        Pending
                        <b><?= $orderStatuses['Pending'] ?></b>
                    </p>

                    <p>
                        <i class="status-processing"></i>
                        Processing
                        <b><?= $orderStatuses['Processing'] ?></b>
                    </p>

                    <p>
                        <i class="status-shipped"></i>
                        Shipped
                        <b><?= $orderStatuses['Shipped'] ?></b>
                    </p>

                    <p>
                        <i class="status-delivered"></i>
                        Delivered
                        <b><?= $orderStatuses['Delivered'] ?></b>
                    </p>

                    <p>
                        <i class="status-cancelled"></i>
                        Cancelled
                        <b><?= $orderStatuses['Cancelled'] ?></b>
                    </p>
                </div>

            </div>

        </article>

    </div>

    <div class="admin-main-grid">

        <article class="admin-card">

            <div class="admin-card-heading">
                <div>
                    <p class="admin-card-label">PRODUCT INSIGHTS</p>
                    <h3>Trending products</h3>
                    <p>Your most popular products</p>
                </div>

                <a
                    class="admin-card-link"
                    href="<?= $base ?>/modules/products/manage.php"
                >
                    Manage products →
                </a>
            </div>

            <?php if (empty($trending)): ?>

                <p class="admin-empty">
                    No product sales recorded yet.
                </p>

            <?php else: ?>

                <div class="admin-product-list">

                    <?php foreach ($trending as $index => $product): ?>

                        <div class="admin-product-row">

                            <span class="product-number">
                                <?= $index + 1 ?>
                            </span>

                            <div class="product-letter">
                                <?= strtoupper(
                                    substr($product['product_name'], 0, 1)
                                ) ?>
                            </div>

                            <div class="product-details">
                                <strong>
                                    <?= htmlspecialchars(
                                        $product['product_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                                <small>Beauty catalogue</small>
                            </div>

                            <b>
                                <?= number_format(
                                    (int) $product['units_sold']
                                ) ?>
                                sold
                            </b>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </article>

        <article class="admin-card">

            <div class="admin-card-heading">
                <div>
                    <p class="admin-card-label">PAYMENT GATEWAY</p>
                    <h3>Payment monitoring</h3>
                    <p>Transaction verification summary</p>
                </div>
            </div>

            <div class="payment-summary">

                <div>
                    <i class="payment-completed"></i>
                    <span>Completed</span>
                    <strong><?= $paymentStatuses['Completed'] ?></strong>
                </div>

                <div>
                    <i class="payment-pending"></i>
                    <span>Pending</span>
                    <strong><?= $paymentStatuses['Pending'] ?></strong>
                </div>

                <div>
                    <i class="payment-failed"></i>
                    <span>Failed</span>
                    <strong><?= $paymentStatuses['Failed'] ?></strong>
                </div>

            </div>

            <?php if (empty($payments)): ?>

                <p class="admin-empty">
                    No payment transactions yet.
                </p>

            <?php else: ?>

                <div class="payment-list">

                    <?php foreach (array_slice($payments, 0, 5) as $payment): ?>

                        <div class="payment-row">

                            <div class="payment-symbol">₨</div>

                            <div class="payment-details">
                                <strong>
                                    Order #<?= (int) $payment['order_id'] ?>
                                </strong>

                                <small>
                                    <?= htmlspecialchars(
                                        $payment['method'] ?? 'Unknown',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </small>
                            </div>

                            <b>
                                Rs.
                                <?= number_format(
                                    (float) $payment['amount'],
                                    2
                                ) ?>
                            </b>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </article>

    </div>

    <article class="admin-card admin-orders-card">

        <div class="admin-card-heading">
            <div>
                <p class="admin-card-label">OPERATIONS</p>
                <h3>Recent orders</h3>
                <p>Customer orders and shipment progress</p>
            </div>
        </div>

        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Order status</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (empty($recentOrders)): ?>

                        <tr>
                            <td colspan="5" class="admin-empty">
                                No orders available.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($recentOrders as $order): ?>

                            <tr>
                                <td>
                                    <strong>
                                        #<?= (int) $order['order_id'] ?>
                                    </strong>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['customer_name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $order['order_date'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </td>

                                <td>
                                    Rs.
                                    <?= number_format(
                                        (float) $order['total_amount'],
                                        2
                                    ) ?>
                                </td>

                                <td>
                                    <?php
                                    $status = strtolower(
                                        $order['order_status']
                                    );
                                    ?>

                                    <span class="order-status <?= $status ?>">
                                        <?= htmlspecialchars(
                                            $order['order_status'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </span>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </article>

</section>