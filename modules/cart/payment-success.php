<?php
/* Stripe redirects here after checkout. We MUST verify the session server-side
 * with Stripe before trusting it (never trust query params alone) — this is
  * the final step of the payment flow. The order is created as Pending in
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/stripe_helper.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$orderId = $_GET['order_id'] ?? null;
$sessionId = $_GET['session_id'] ?? null;
$userId = current_user()['user_id'];

if (!$orderId || !$sessionId) {
    die('Missing order or session reference.');
}

// Confirm the order belongs to this user
$orderStmt = $pdo->prepare("SELECT * FROM `Order` WHERE order_id = ? AND user_id = ?");
$orderStmt->execute([$orderId, $userId]);
$order = $orderStmt->fetch();
if (!$order) {
    die('Order not found.');
}

// Verify with Stripe directly — do not trust the browser
$session = stripe_api_request('GET', '/checkout/sessions/' . urlencode($sessionId));

if (($session['payment_status'] ?? '') === 'paid') {
    // Only finalize once — if already Processing/Paid, skip re-inserting
    if ($order['order_status'] === 'Pending') {
        $pdo->beginTransaction();
        try {
            $pdo->prepare("UPDATE `Order` SET order_status = 'Processing' WHERE order_id = ?")->execute([$orderId]);

            $pdo->prepare(
                "INSERT INTO Payment (order_id, transaction_id, method, amount, status) VALUES (?, ?, 'card', ?, 'Completed')"
            )->execute([$orderId, $session['payment_intent'] ?? $sessionId, $order['total_amount']]);

            $pdo->prepare("INSERT INTO Order_History (order_id, order_history_status) VALUES (?, 'Processing')")
                ->execute([$orderId]);

            // Clear the cart now that payment is confirmed
            $cartStmt = $pdo->prepare("SELECT cart_id FROM Cart WHERE user_id = ?");
            $cartStmt->execute([$userId]);
            $cartId = $cartStmt->fetchColumn();
            $pdo->prepare("DELETE FROM Cart_Item WHERE cart_id = ?")->execute([$cartId]);

            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollBack();
            die('Error finalizing order: ' . $e->getMessage());
        }
    }

    header('Location: ' . app_url('/modules/orders/track-order.php?order_id=' . $orderId . '&paid=1'));
    exit;
} else {
    // Payment did not succeed — leave order as Pending, let the user retry
    header('Location: ' . app_url('/modules/cart/payment-cancel.php?order_id=' . $orderId));
    exit;
}
