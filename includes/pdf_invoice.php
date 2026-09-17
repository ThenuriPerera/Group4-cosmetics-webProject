<?php
/**
 * Invoice helper kept for compatibility.
 * The reliable implementation is the browser printable HTML invoice in
 * modules/orders/download-invoice.php.
 */

function build_invoice_pdf(array $data): string
{
    $order = $data['order'] ?? [];
    $items = $data['items'] ?? [];
    $payment = $data['payment'] ?? [];
    $customer = $data['customer'] ?? [];

    $orderId = (int) ($order['order_id'] ?? 0);
    $customerName = trim((string) ($customer['name'] ?? $order['customer_name'] ?? 'Customer'));
    $paymentDate = $payment['payment_date'] ?? $order['order_date'] ?? 'N/A';
    $totalAmount = number_format((float) ($payment['amount'] ?? $order['total_amount'] ?? 0), 2);

    $lines = [
        '<!DOCTYPE html>',
        '<html><head><meta charset="UTF-8"><title>Invoice #' . $orderId . '</title></head><body>',
        '<h1>Payment Receipt</h1>',
        '<p>Order #: ' . $orderId . '</p>',
        '<p>Customer: ' . htmlspecialchars($customerName) . '</p>',
        '<p>Payment Date: ' . htmlspecialchars($paymentDate) . '</p>',
        '<p>Total Paid: Rs. ' . htmlspecialchars($totalAmount) . '</p>',
    ];

    foreach ($items as $item) {
        $name = htmlspecialchars((string) ($item['product_name'] ?? 'Product'));
        $qty = (int) ($item['quantity'] ?? 1);
        $price = number_format((float) ($item['price'] ?? 0), 2);
        $lines[] = '<p>' . $name . ' x' . $qty . ' @ Rs. ' . $price . '</p>';
    }

    $lines[] = '</body></html>';

    return implode("\n", $lines);
}
