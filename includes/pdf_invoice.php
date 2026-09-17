<?php

function pdf_escape_text(string $value): string
{
    $value = str_replace('\\', '\\\\', $value);
    $value = str_replace('(', '\\(', $value);
    $value = str_replace(')', '\\)', $value);
    $value = str_replace("\r\n", '\n', $value);
    $value = str_replace("\n", ' ', $value);
    return $value;
}

function pdf_text_line(float $x, float $y, string $text, int $size = 11, bool $bold = false): string
{
    $font = $bold ? 'F2' : 'F1';
    return "BT\n/$font $size Tf\n1 0 0 1 $x $y Tm\n(" . pdf_escape_text($text) . ") Tj\nET\n";
}

function build_invoice_pdf(array $data): string
{
    $order = $data['order'] ?? [];
    $items = $data['items'] ?? [];
    $payment = $data['payment'] ?? [];
    $customer = $data['customer'] ?? [];

    $orderId = (int) ($order['order_id'] ?? 0);
    $orderDate = $order['order_date'] ?? 'N/A';
    $totalAmount = number_format((float) ($order['total_amount'] ?? 0), 2);
    $status = $order['order_status'] ?? 'Pending';
    $transactionId = $payment['transaction_id'] ?? 'N/A';

    $customerName = trim((string) ($customer['name'] ?? $order['customer_name'] ?? 'Customer'));
    $customerEmail = trim((string) ($customer['email'] ?? $order['customer_email'] ?? ''));
    $shippingAddress = [];
    foreach (['street', 'city', 'state', 'postal_code', 'country'] as $key) {
        $value = trim((string) ($order[$key] ?? ''));
        if ($value !== '') {
            $shippingAddress[] = $value;
        }
    }
    $shippingText = implode(', ', $shippingAddress);
    if ($shippingText === '') {
        $shippingText = 'Delivery address not available';
    }

    $content = "BT\n/F1 20 Tf\n1 0 0 1 50 790 Tm\n(Lumine Glow - Payment Receipt) Tj\nET\n";
    $content .= pdf_text_line(50, 760, 'Order ID: ' . $orderId, 11);
    $content .= pdf_text_line(50, 742, 'Order Date: ' . $orderDate, 11);
    $content .= pdf_text_line(50, 724, 'Status: ' . $status, 11);
    $content .= pdf_text_line(50, 706, 'Customer: ' . $customerName, 11);
    if ($customerEmail !== '') {
        $content .= pdf_text_line(50, 688, 'Email: ' . $customerEmail, 11);
    }
    $content .= pdf_text_line(50, 670, 'Shipping Address: ' . $shippingText, 11);
    if ($transactionId !== 'N/A') {
        $content .= pdf_text_line(50, 652, 'Transaction ID: ' . $transactionId, 11);
    }

    $content .= "BT\n/F2 13 Tf\n1 0 0 1 50 626 Tm\n(Item Summary) Tj\nET\n";

    $y = 606;
    foreach ($items as $index => $item) {
        $name = trim((string) ($item['product_name'] ?? 'Product'));
        $qty = (int) ($item['quantity'] ?? 1);
        $unitPrice = number_format((float) ($item['price'] ?? 0), 2);
        $line = ($index + 1) . '. ' . $name . ' x' . $qty . ' @ Rs. ' . $unitPrice;
        $content .= pdf_text_line(50, $y, $line, 10);
        $y -= 18;
    }

    if (empty($items)) {
        $content .= pdf_text_line(50, $y, 'No items recorded for this order.', 10);
        $y -= 18;
    }

    $content .= "BT\n/F2 13 Tf\n1 0 0 1 50 " . ($y - 10) . " Tm\n(Total Paid: Rs. " . $totalAmount . ") Tj\nET\n";

    $objects = [
        '<< /Type /Catalog /Pages 2 0 R >>',
        '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
        '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R /F2 6 0 R >> >> /Contents 5 0 R >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
    ];

    $contentLength = strlen($content);
    $contentObject = '<< /Length ' . $contentLength . ' >>\nstream\n' . $content . '\nendstream';
    $objects[] = $contentObject;

    $pdf = "%PDF-1.4\n";
    $offsets = [0];
    foreach ($objects as $index => $object) {
        $offsets[$index + 1] = strlen($pdf);
        $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
    }

    $xrefPosition = strlen($pdf);
    $objectCount = count($objects) + 1;
    $pdf .= "xref\n0 $objectCount\n";
    $pdf .= "0000000000 65535 f \n";
    for ($i = 1; $i <= count($objects); $i++) {
        $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
    }

    $pdf .= "trailer\n<< /Size $objectCount /Root 1 0 R >>\nstartxref\n$xrefPosition\n%%EOF\n";

    return $pdf;
}
