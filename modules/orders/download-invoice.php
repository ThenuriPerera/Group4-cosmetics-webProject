<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$orderId = (int) ($_GET['order_id'] ?? 0);

if ($orderId <= 0) {
    http_response_code(400);
    die('Missing order ID.');
}

$orderStmt = $pdo->prepare(
    "SELECT o.*, u.name AS customer_name, u.email AS customer_email,
            a.street, a.city, a.state, a.postal_code, a.country,
            p.transaction_id, p.amount AS paid_amount, p.method, p.payment_date
     FROM `Order` o
     JOIN `User` u ON u.user_id = o.user_id
     LEFT JOIN Address a ON a.address_id = o.address_id
     LEFT JOIN Payment p ON p.order_id = o.order_id
     WHERE o.order_id = ? AND o.user_id = ?
     ORDER BY p.payment_date DESC, p.payment_id DESC
     LIMIT 1"
);
$orderStmt->execute([$orderId, $userId]);
$order = $orderStmt->fetch();

if (!$order) {
    http_response_code(404);
    die('Order not found.');
}

$itemStmt = $pdo->prepare(
    "SELECT oi.product_id, oi.quantity, oi.price, p.product_name
     FROM Order_Item oi
     JOIN Product p ON p.product_id = oi.product_id
     WHERE oi.order_id = ?"
);
$itemStmt->execute([$orderId]);
$items = $itemStmt->fetchAll();

$customerName = trim((string) ($order['customer_name'] ?? 'Customer'));
$customerEmail = trim((string) ($order['customer_email'] ?? ''));
$orderDate = $order['order_date'] ?? 'N/A';
$paymentDate = $order['payment_date'] ?? $orderDate;
$transactionId = $order['transaction_id'] ?? 'N/A';
$paymentMethod = $order['method'] ?? 'Card';
$totalAmount = number_format((float) ($order['paid_amount'] ?? $order['total_amount'] ?? 0), 2);

$shippingParts = [];
foreach (['street', 'city', 'state', 'postal_code', 'country'] as $key) {
    $value = trim((string) ($order[$key] ?? ''));
    if ($value !== '') {
        $shippingParts[] = $value;
    }
}
$shippingAddress = implode(', ', $shippingParts) ?: 'Delivery address not available';

$subTotal = 0;
foreach ($items as $item) {
    $subTotal += (float) ($item['price'] ?? 0) * (int) ($item['quantity'] ?? 1);
}

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - Order #<?= (int) $orderId ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            background: #f5f3f0;
            color: #1f1f1f;
        }
        .page {
            max-width: 820px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 0 18px rgba(0,0,0,0.08);
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e8d6cb;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 30px;
            font-weight: 700;
            color: #9c5f41;
            letter-spacing: 1px;
        }
        .meta {
            text-align: right;
            font-size: 14px;
            line-height: 1.8;
        }
        .section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin: 24px 0;
        }
        .block {
            background: #faf6f3;
            border: 1px solid #f0e2d9;
            padding: 16px 18px;
            border-radius: 8px;
        }
        h3 {
            margin: 0 0 12px;
            font-size: 14px;
            color: #7b4e35;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .details, .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .details td {
            padding: 8px 0;
            border-bottom: 1px solid #f1e6df;
            font-size: 14px;
        }
        .details td:first-child {
            color: #6d6d6d;
            width: 140px;
        }
        .items th, .items td {
            border-bottom: 1px solid #f0e0d5;
            padding: 12px 8px;
            text-align: left;
            font-size: 14px;
        }
        .items th {
            background: #f7efe9;
            color: #57392a;
            font-weight: 700;
        }
        .total-box {
            margin-top: 20px;
            margin-left: auto;
            width: 280px;
            background: #faf6f3;
            border: 1px solid #f0e2d9;
            border-radius: 8px;
            padding: 18px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 15px;
        }
        .total-row.final {
            font-size: 22px;
            font-weight: 700;
            color: #9c5f41;
            border-top: 1px solid #e9d3c7;
            padding-top: 12px;
            margin-top: 14px;
        }
        .print-btn {
            display: none;
        }
        @media print {
            body {
                background: #fff;
            }
            .page {
                box-shadow: none;
                margin: 0;
                max-width: none;
                padding: 0;
            }
            .print-btn {
                display: none !important;
            }
        }
        @media screen {
            .print-btn {
                display: inline-block;
                margin-top: 18px;
                background: #9c5f41;
                color: #fff;
                border: 0;
                padding: 10px 18px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="brand">LUMINE GLOW</div>
                <div style="margin-top:8px; color:#5d5d5d; font-size:14px;">Payment Receipt</div>
            </div>
            <div class="meta">
                <div><strong>Invoice #</strong> <?= (int) $orderId ?></div>
                <div><strong>Date:</strong> <?= htmlspecialchars($orderDate) ?></div>
                <div><strong>Paid:</strong> <?= htmlspecialchars($paymentDate) ?></div>
            </div>
        </div>

        <div class="section">
            <div class="block">
                <h3>Customer</h3>
                <div style="font-size: 14px; line-height: 1.8;">
                    <div><strong><?= htmlspecialchars($customerName) ?></strong></div>
                    <div><?= htmlspecialchars($customerEmail) ?></div>
                </div>
            </div>
            <div class="block">
                <h3>Shipping Address</h3>
                <div style="font-size: 14px; line-height: 1.8;">
                    <?= htmlspecialchars($shippingAddress) ?>
                </div>
            </div>
        </div>

        <div class="block">
            <h3>Payment Details</h3>
            <table class="details">
                <tr>
                    <td>Payment Status</td>
                    <td><?= htmlspecialchars($order['order_status'] ?? 'Completed') ?></td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td><?= htmlspecialchars($paymentMethod) ?></td>
                </tr>
                <tr>
                    <td>Transaction ID</td>
                    <td><?= htmlspecialchars($transactionId) ?></td>
                </tr>
            </table>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php
                    $qty = (int) ($item['quantity'] ?? 1);
                    $unitPrice = (float) ($item['price'] ?? 0);
                    $lineTotal = $qty * $unitPrice;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars((string) ($item['product_name'] ?? 'Product')) ?></td>
                        <td><?= $qty ?></td>
                        <td>Rs. <?= number_format($unitPrice, 2) ?></td>
                        <td>Rs. <?= number_format($lineTotal, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="total-box">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rs. <?= number_format((float) $subTotal, 2) ?></span>
            </div>
            <div class="total-row final">
                <span>Total Paid</span>
                <span>Rs. <?= htmlspecialchars($totalAmount) ?></span>
            </div>
        </div>

        <button class="print-btn" onclick="window.print()">Save as PDF</button>
    </div>

    <script>
        window.onload = function () {
            setTimeout(function () {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
