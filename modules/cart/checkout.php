<?php
/* Status: COMPLETE — inline "add new address" form + promo code validation
 (checks existence and expiry_date before letting it through to payment).
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$promoError = '';

// Inline add address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $pdo->prepare(
        "INSERT INTO Address (user_id, street, city, postal_code, state, country) VALUES (?, ?, ?, ?, ?, ?)"
    )->execute([
        $userId,
        trim($_POST['street']),
        trim($_POST['city']),
        trim($_POST['postal_code']),
        trim($_POST['state']),
        trim($_POST['country']),
    ]);
    header('Location: /modules/cart/checkout.php');
    exit;
}

// Validate promo code before moving to payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['continue_to_payment'])) {
    $promoCode = trim($_POST['promo_code'] ?? '');
    $addressId = $_POST['address_id'] ?? null;

    if (!$addressId) {
        $promoError = 'Please select a delivery address.';
    } else {
        $promoId = null;
        if ($promoCode !== '') {
            $stmt = $pdo->prepare("SELECT * FROM Promo_Code WHERE code = ?");
            $stmt->execute([$promoCode]);
            $promo = $stmt->fetch();

            if (!$promo) {
                $promoError = 'Invalid promo code.';
            } elseif ($promo['expiry_date'] && strtotime($promo['expiry_date']) < time()) {
                $promoError = 'This promo code has expired.';
            } else {
                $promoId = $promo['promo_id'];
            }
        }

        if (!$promoError) {
            header('Location: /modules/cart/payment.php?address_id=' . $addressId . '&promo_id=' . ($promoId ?? ''));
            exit;
        }
    }
}

$addresses = $pdo->prepare("SELECT * FROM Address WHERE user_id = ?");
$addresses->execute([$userId]);
$addresses = $addresses->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="checkout-page">
    <h1>Checkout</h1>
    <?php if ($promoError): ?><p class="error"><?= htmlspecialchars($promoError) ?></p><?php endif; ?>

    <form method="post">
        <h2>Delivery Address</h2>
        <?php if ($addresses): ?>
            <?php foreach ($addresses as $addr): ?>
                <label>
                    <input type="radio" name="address_id" value="<?= $addr['address_id'] ?>" required>
                    <?= htmlspecialchars("{$addr['street']}, {$addr['city']}, {$addr['country']}") ?>
                </label><br>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No saved addresses yet — add one below.</p>
        <?php endif; ?>

        <h2>Promo Code</h2>
        <input type="text" name="promo_code" placeholder="Enter code (optional)">

        <button type="submit" name="continue_to_payment">Continue to Payment</button>
    </form>

    <h3>Add New Address</h3>
    <form method="post">
        <label>Street <input type="text" name="street" required></label>
        <label>City <input type="text" name="city" required></label>
        <label>State/Province <input type="text" name="state"></label>
        <label>Postal Code <input type="text" name="postal_code"></label>
        <label>Country <input type="text" name="country" required></label>
        <button type="submit" name="add_address">Add Address</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
