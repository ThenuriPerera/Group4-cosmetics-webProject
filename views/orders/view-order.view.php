<?php
$orderId = (int) $order['order_id'];

$orderStatus = $order['order_status'] ?? 'Pending';
$trackingNumber = $shipment['tracking_number'] ?? null;
$courierName = $shipment['company_name'] ?? null;
$courierPhone = $shipment['contact_number'] ?? null;
$deliveryStatus = $shipment['delivery_status'] ?? $orderStatus;
$estimatedDelivery = $shipment['estimated_delivery'] ?? null;

function order_status_class($status)
{
    return strtolower(str_replace(' ', '-', $status));
}

function order_format_date($date)
{
    if (!$date) {
        return '—';
    }

    return date('d M Y, h:i A', strtotime($date));
}
?>

<style>
    .lg-order-details {
        max-width: 1180px;
        margin: 40px auto;
        padding: 0 20px 60px;
        color: #3d3037;
    }

    .lg-order-hero {
        background: linear-gradient(135deg, #fff5f7, #f9eef2);
        border-radius: 28px;
        padding: 35px;
        margin-bottom: 25px;
        border: 1px solid #f0dce2;
    }

    .lg-order-hero h1 {
        margin: 0 0 8px;
        font-size: 32px;
        color: #432f37;
    }

    .lg-order-hero p {
        margin: 0;
        color: #806d74;
    }

    .lg-order-number {
        margin-top: 18px;
        display: inline-block;
        background: #ffffff;
        border: 1px solid #ead6dd;
        padding: 9px 15px;
        border-radius: 30px;
        font-weight: 700;
        color: #9a536b;
    }

    .lg-details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 22px;
    }

    .lg-card {
        background: #ffffff;
        border: 1px solid #eadfe3;
        border-radius: 22px;
        padding: 25px;
        margin-bottom: 22px;
        box-shadow: 0 8px 25px rgba(90, 60, 70, 0.06);
    }

    .lg-card h2 {
        margin: 0 0 20px;
        font-size: 20px;
        color: #49353d;
    }

    .lg-item {
        display: flex;
        gap: 18px;
        padding: 18px 0;
        border-bottom: 1px solid #f1e8eb;
    }

    .lg-item:last-child {
        border-bottom: none;
    }

    .lg-item-image {
        width: 90px;
        height: 90px;
        border-radius: 16px;
        object-fit: cover;
        background: #faf3f5;
        border: 1px solid #eee0e5;
    }

    .lg-item-info {
        flex: 1;
    }

    .lg-item-info h3 {
        margin: 0 0 7px;
        font-size: 17px;
        color: #44323a;
    }

    .lg-item-info p {
        margin: 0 0 8px;
        color: #817178;
        font-size: 14px;
    }

    .lg-item-meta {
        color: #9b6879;
        font-size: 14px;
        font-weight: 600;
    }

    .lg-item-total {
        font-weight: 800;
        color: #49343d;
        white-space: nowrap;
    }

    .lg-total {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 2px solid #f0e5e9;
        font-size: 20px;
        font-weight: 800;
    }

    .lg-info-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 12px 0;
        border-bottom: 1px solid #f1e8eb;
    }

    .lg-info-row:last-child {
        border-bottom: none;
    }

    .lg-label {
        color: #8a777e;
    }

    .lg-value {
        font-weight: 700;
        text-align: right;
        color: #4b3840;
    }

    .lg-status {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 20px;
        background: #f8e7ed;
        color: #96516b;
        font-size: 13px;
        font-weight: 800;
    }

    .lg-timeline {
        position: relative;
        padding-left: 30px;
    }

    .lg-timeline::before {
        content: "";
        position: absolute;
        left: 8px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: #ead8df;
    }

    .lg-history-item {
        position: relative;
        padding: 0 0 22px;
    }

    .lg-history-item:last-child {
        padding-bottom: 0;
    }

    .lg-history-dot {
        position: absolute;
        left: -28px;
        top: 3px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #c87591;
        border: 3px solid #fff5f7;
        box-shadow: 0 0 0 2px #e7cbd4;
    }

    .lg-history-status {
        font-weight: 800;
        color: #4a3740;
    }

    .lg-history-time {
        margin-top: 4px;
        color: #8c7b82;
        font-size: 13px;
    }

    .lg-empty {
        color: #8b7b81;
        font-size: 14px;
    }

    .lg-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
    }

    .lg-btn {
        display: inline-block;
        text-decoration: none;
        border-radius: 13px;
        padding: 12px 18px;
        font-weight: 800;
        transition: 0.2s ease;
    }

    .lg-btn-primary {
        background: #a85d76;
        color: white;
    }

    .lg-btn-primary:hover {
        background: #8f4c63;
    }

    .lg-btn-secondary {
        background: #f8eef1;
        color: #895168;
        border: 1px solid #ead8df;
    }

    .lg-btn-secondary:hover {
        background: #f3e2e7;
    }

    @media (max-width: 800px) {
        .lg-details-grid {
            grid-template-columns: 1fr;
        }

        .lg-order-hero {
            padding: 25px;
        }

        .lg-order-hero h1 {
            font-size: 26px;
        }
    }

    @media (max-width: 560px) {
        .lg-item {
            align-items: flex-start;
        }

        .lg-item-image {
            width: 70px;
            height: 70px;
        }

        .lg-item-total {
            font-size: 14px;
        }

        .lg-info-row {
            flex-direction: column;
            gap: 4px;
        }

        .lg-value {
            text-align: left;
        }
    }
</style>

<div class="lg-order-details">

    <section class="lg-order-hero">
        <h1>Order Details</h1>
        <p>Everything about your Lumine Glow order, beautifully organized.</p>

        <div class="lg-order-number">
            Order #<?= htmlspecialchars((string) $orderId) ?>
        </div>
    </section>

    <div class="lg-details-grid">

        <div>

            <section class="lg-card">
                <h2>🛍️ Your Items</h2>

                <?php if (!empty($orderItems)): ?>

                    <?php foreach ($orderItems as $item): ?>

                        <?php
                        $image = trim((string) ($item['image'] ?? ''));
                        $imageUrl = $image !== ''
                            ? lg_url('/assets/images/' . ltrim($image, '/'))
                            : null;

                        $lineTotal = (float) $item['price'] * (int) $item['quantity'];
                        ?>

                        <div class="lg-item">

                            <?php if ($imageUrl): ?>
                                <img
                                    src="<?= htmlspecialchars($imageUrl) ?>"
                                    alt="<?= htmlspecialchars($item['product_name']) ?>"
                                    class="lg-item-image"
                                >
                            <?php else: ?>
                                <div class="lg-item-image"></div>
                            <?php endif; ?>

                            <div class="lg-item-info">
                                <h3>
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </h3>

                                <?php if (!empty($item['short_description'])): ?>
                                    <p>
                                        <?= htmlspecialchars($item['short_description']) ?>
                                    </p>
                                <?php endif; ?>

                                <div class="lg-item-meta">
                                    Quantity: <?= (int) $item['quantity'] ?>
                                    &nbsp; • &nbsp;
                                    LKR <?= number_format((float) $item['price'], 2) ?>
                                </div>
                            </div>

                            <div class="lg-item-total">
                                LKR <?= number_format($lineTotal, 2) ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="lg-empty">No items found for this order.</p>

                <?php endif; ?>

                <div class="lg-total">
                    <span>Order Total</span>
                    <span>
                        LKR <?= number_format((float) $order['total_amount'], 2) ?>
                    </span>
                </div>

            </section>

            <section class="lg-card">
                <h2>📦 Order History</h2>

                <?php if (!empty($orderHistory)): ?>

                    <div class="lg-timeline">

                        <?php foreach ($orderHistory as $history): ?>

                            <div class="lg-history-item">

                                <div class="lg-history-dot"></div>

                                <div class="lg-history-status">
                                    <?= htmlspecialchars($history['order_history_status'] ?? 'Updated') ?>
                                </div>

                                <div class="lg-history-time">
                                    <?= htmlspecialchars(order_format_date($history['time_stamp'] ?? null)) ?>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <p class="lg-empty">
                        No tracking history is available yet.
                    </p>

                <?php endif; ?>

            </section>

        </div>

        <div>

            <section class="lg-card">
                <h2>📋 Order Summary</h2>

                <div class="lg-info-row">
                    <span class="lg-label">Order ID</span>
                    <span class="lg-value">#<?= $orderId ?></span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Order Date</span>
                    <span class="lg-value">
                        <?= htmlspecialchars(order_format_date($order['order_date'])) ?>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Status</span>
                    <span class="lg-value">
                        <span class="lg-status">
                            <?= htmlspecialchars($orderStatus) ?>
                        </span>
                    </span>
                </div>

            </section>

            <section class="lg-card">
                <h2>🚚 Delivery</h2>

                <div class="lg-info-row">
                    <span class="lg-label">Courier</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($courierName ?: 'Not assigned') ?>
                    </span>
                </div>

                <?php if ($courierPhone): ?>
                    <div class="lg-info-row">
                        <span class="lg-label">Courier Phone</span>
                        <span class="lg-value">
                            <?= htmlspecialchars($courierPhone) ?>
                        </span>
                    </div>
                <?php endif; ?>

                <div class="lg-info-row">
                    <span class="lg-label">Tracking Number</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($trackingNumber ?: 'Not available') ?>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Delivery Status</span>
                    <span class="lg-value">
                        <span class="lg-status">
                            <?= htmlspecialchars($deliveryStatus ?: 'Pending') ?>
                        </span>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Estimated Delivery</span>
                    <span class="lg-value">
                        <?php if ($estimatedDelivery): ?>
                            <?= htmlspecialchars(date('d M Y', strtotime($estimatedDelivery))) ?>
                        <?php else: ?>
                            Not available
                        <?php endif; ?>
                    </span>
                </div>

            </section>

            <section class="lg-card">
                <h2>📍 Delivery Address</h2>

                <div class="lg-info-row">
                    <span class="lg-label">Street</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($order['street'] ?? '—') ?>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">City</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($order['city'] ?? '—') ?>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Postal Code</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($order['postal_code'] ?? '—') ?>
                    </span>
                </div>

                <div class="lg-info-row">
                    <span class="lg-label">Country</span>
                    <span class="lg-value">
                        <?= htmlspecialchars($order['country'] ?? '—') ?>
                    </span>
                </div>

            </section>

        </div>

    </div>

    <div class="lg-actions">

        <a
            href="<?= htmlspecialchars(lg_url('/modules/orders/track-order.php?order_id=' . $orderId)) ?>"
            class="lg-btn lg-btn-primary"
        >
            ← Track This Order
        </a>

        <a
            href="<?= htmlspecialchars(lg_url('/modules/orders/track-order.php')) ?>"
            class="lg-btn lg-btn-secondary"
        >
            All Orders
        </a>

    </div>

</div>