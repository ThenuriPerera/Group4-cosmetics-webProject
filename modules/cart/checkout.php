<?php
/**
 * MODULE OWNER: Member 3 (Cart, Checkout, Payment, Promo Codes)
 * Section 5.4 - Checkout & Address
 * TODO (Member 3):
 *  - Add "add new address" inline form
 *  - Validate promo code (expiry_date, apply discount_percentage)
 *  - On submit -> create Order + Order_Item rows, then redirect to payment.php
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];

$addresses = $pdo->prepare("SELECT * FROM Address WHERE user_id = ?");
$addresses->execute([$userId]);
$addresses = $addresses->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="checkout-page">
    <h1>Checkout</h1>

    <form method="post" action="/modules/cart/payment.php">
        <h2>Delivery Address</h2>
        <?php if ($addresses): ?>
            <?php foreach ($addresses as $addr): ?>
                <label>
                    <input type="radio" name="address_id" value="<?= $addr['address_id'] ?>" required>
                    <?= htmlspecialchars("{$addr['street']}, {$addr['city']}, {$addr['country']}") ?>
                </label><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No saved addresses. <!-- TODO: inline add-address form --></p>
        <?php endif; ?>

        <h2>Promo Code</h2>
        <input type="text" name="promo_code" placeholder="Enter code (optional)">

        <button type="submit">Continue to Payment</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
