<?php
/**
 * MODULE OWNER: Member 3 (Cart, Checkout, Payment, Promo Codes)
 * Section 5.5 - Payment Integration
 * TODO (Member 3):
 *  - Replace the mock transaction with a real Stripe/PayPal API call
 *  - NEVER store card details — only transaction_id, method, status (Section 5.5)
 *  - On success: create Order, Order_Item rows, clear cart, insert Payment + Order_History row
 *  - On failure: show error, keep cart intact
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$addressId = $_POST['address_id'] ?? null;
$promoCode = trim($_POST['promo_code'] ?? '');

if (!$addressId) {
    die('Address is required.');
}

// Get cart total
$cart = $pdo->prepare("SELECT cart_id FROM Cart WHERE user_id = ?");
$cart->execute([$userId]);
$cartId = $cart->fetchColumn();

$items = $pdo->prepare(
    "SELECT ci.product_id, ci.quantity, p.price
     FROM Cart_Item ci JOIN Product p ON ci.product_id = p.product_id
     WHERE ci.cart_id = ?"
);
$items->execute([$cartId]);
$cartItems = $items->fetchAll();

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// TODO: apply promo code discount to $total here

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $pdo->beginTransaction();
    try {
        // 1. Create Order
        $pdo->prepare(
            "INSERT INTO `Order` (user_id, address_id, total_amount, order_status) VALUES (?, ?, ?, 'Pending')"
        )->execute([$userId, $addressId, $total]);
        $orderId = $pdo->lastInsertId();

        // 2. Move cart items -> Order_Item
        foreach ($cartItems as $item) {
            $pdo->prepare(
                "INSERT INTO Order_Item (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)"
            )->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
        }

        // 3. TODO: call Stripe/PayPal API here instead of faking it
        $fakeTransactionId = 'TXN' . time();

        $pdo->prepare(
            "INSERT INTO Payment (order_id, transaction_id, method, amount, status) VALUES (?, ?, ?, ?, 'Completed')"
        )->execute([$orderId, $fakeTransactionId, 'card', $total]);

        // 4. Log order history
        $pdo->prepare(
            "INSERT INTO Order_History (order_id, order_history_status) VALUES (?, 'Pending')"
        )->execute([$orderId]);

        // 5. Clear cart
        $pdo->prepare("DELETE FROM Cart_Item WHERE cart_id = ?")->execute([$cartId]);

        $pdo->commit();
        header('Location: /modules/orders/track-order.php?order_id=' . $orderId);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        die('Payment failed: ' . $e->getMessage());
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="payment-page">
    <h1>Payment</h1>
    <p>Order Total: Rs. <?= number_format($total, 2) ?></p>

    <!-- TODO: replace with real Stripe Elements / PayPal button SDK -->
    <form method="post">
        <input type="hidden" name="address_id" value="<?= htmlspecialchars($addressId) ?>">
        <input type="hidden" name="promo_code" value="<?= htmlspecialchars($promoCode) ?>">
        <label>Payment Method
            <select name="method">
                <option value="card">Credit / Debit Card</option>
                <option value="paypal">PayPal</option>
            </select>
        </label>
        <button type="submit" name="confirm_payment">Pay Now</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
