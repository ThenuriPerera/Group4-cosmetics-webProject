<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="orders-page">

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
    <?php if (empty($orders)): ?><div class="empty-state"><h2>Your next favourite is waiting.</h2><p>Your orders will appear here after checkout.</p><a class="btn" href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Explore the collection</a></div><?php endif; ?>
</section>
