<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/pdf_invoice.php';
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
            p.transaction_id, p.amount AS paid_amount
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

$pdf = build_invoice_pdf([
    'order' => $order,
    'items' => $items,
    'payment' => [
        'transaction_id' => $order['transaction_id'] ?? 'N/A',
        'amount' => $order['paid_amount'] ?? $order['total_amount'],
    ],
    'customer' => [
        'name' => $order['customer_name'] ?? 'Customer',
        'email' => $order['customer_email'] ?? '',
    ],
]);

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="invoice-order-' . $orderId . '.pdf"');
header('Content-Length: ' . strlen($pdf));
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
echo $pdf;
exit;
