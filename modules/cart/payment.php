<?php
/* Status: COMPLETE — real Stripe Checkout (TEST MODE) via raw cURL to the
 * Stripe REST API 
 * created as Pending first, then finalized only after Stripe confirms payment
 * in payment-success.php. No card data ever touches our server or database */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/stripe_helper.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$addressId = $_GET['address_id'] ?? null;
$promoId = $_GET['promo_id'] ?: null;

if (!$addressId) {
    die('Address is required.');
}

$cartStmt = $pdo->prepare("SELECT cart_id FROM Cart WHERE user_id = ?");
$cartStmt->execute([$userId]);
$cartId = $cartStmt->fetchColumn();

$items = $pdo->prepare(
    "SELECT ci.product_id, ci.quantity, p.price, p.product_name
     FROM Cart_Item ci JOIN Product p ON ci.product_id = p.product_id
     WHERE ci.cart_id = ?"
);
$items->execute([$cartId]);
$cartItems = $items->fetchAll();

if (empty($cartItems)) {
    die('Your cart is empty.');
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Apply promo discount
$discountPct = 0;
if ($promoId) {
    $promoStmt = $pdo->prepare("SELECT discount_percentage FROM Promo_Code WHERE promo_id = ?");
    $promoStmt->execute([$promoId]);
    $discountPct = (float)$promoStmt->fetchColumn();
}
$discountedTotal = round($total * (1 - $discountPct / 100), 2);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_stripe_checkout'])) {
    // 1. Create the Order as Pending BEFORE redirecting to Stripe
    $pdo->beginTransaction();
    try {
        $pdo->prepare(
            "INSERT INTO `Order` (user_id, address_id, total_amount, order_status) VALUES (?, ?, ?, 'Pending')"
        )->execute([$userId, $addressId, $discountedTotal]);
        $orderId = $pdo->lastInsertId();

        foreach ($cartItems as $item) {
            $pdo->prepare(
                "INSERT INTO Order_Item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
            )->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        }

        $pdo->prepare("INSERT INTO Order_History (order_id, order_history_status) VALUES (?, 'Pending')")
            ->execute([$orderId]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        die('Could not create order: ' . $e->getMessage());
    }

    // 2. Build Stripe Checkout Session line items
    $baseUrl = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $postFields = [
        'mode' => 'payment',
        'success_url' => $baseUrl . '/modules/cart/payment-success.php?order_id=' . $orderId . '&session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $baseUrl . '/modules/cart/payment-cancel.php?order_id=' . $orderId,
        'metadata' => ['order_id' => $orderId, 'user_id' => $userId],
    ];

    $lineIndex = 0;
    foreach ($cartItems as $item) {
        $unitAmount = (int) round($item['price'] * (1 - $discountPct / 100) * 100); // cents, discount applied per line
        $postFields["line_items[$lineIndex][price_data][currency]"] = 'usd';
        $postFields["line_items[$lineIndex][price_data][product_data][name]"] = $item['product_name'];
        $postFields["line_items[$lineIndex][price_data][unit_amount]"] = $unitAmount;
        $postFields["line_items[$lineIndex][quantity]"] = $item['quantity'];
        $lineIndex++;
    }

    $session = stripe_api_request('POST', '/checkout/sessions', $postFields);

    if (isset($session['url'])) {
        header('Location: ' . $session['url']);
        exit;
    } else {
        die('Stripe error: ' . htmlspecialchars($session['error']['message'] ?? 'Unknown error'));
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="payment-page">
    <h1>Payment</h1>
    <?php if ($discountPct > 0): ?>
        <p>Subtotal: Rs. <?= number_format($total, 2) ?> — Promo discount: <?= $discountPct ?>%</p>
    <?php endif; ?>
    <p>Order Total: Rs. <?= number_format($discountedTotal, 2) ?></p>
    <p>You'll be redirected to Stripe's secure checkout page (test mode — use card <code>4242 4242 4242 4242</code>, any future date, any CVC).</p>

    <form method="post">
        <button type="submit" name="start_stripe_checkout">Pay with Stripe</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
