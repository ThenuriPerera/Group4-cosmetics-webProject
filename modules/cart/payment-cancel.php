<?php
/* Shown when Stripe checkout is cancelled or payment fails.
 The Order row stays 'Pending' and the cart is left intact so the user can retry.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$orderId = $_GET['order_id'] ?? null;
require_once __DIR__ . '/../../includes/header.php';
?>
<section class="payment-page">
    <h1>Payment Cancelled</h1>
    <p>Your payment was not completed. No charge was made, and your cart items are still saved.</p>
    <?php if ($orderId): ?>
        <a class="btn" href="<?= app_url('/modules/cart/checkout.php') ?>">Try Again</a>
    <?php endif; ?>
    <a href="<?= app_url('/modules/cart/cart.php') ?>">Back to Cart</a>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
