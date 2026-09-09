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
    header('Location: ' . lg_url('/modules/cart/checkout.php'));
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
            header('Location: ' . lg_url('/modules/cart/payment.php?address_id=') . $addressId . '&promo_id=' . ($promoId ?? ''));
            exit;
        }
    }
}

$addresses = $pdo->prepare("SELECT * FROM Address WHERE user_id = ?");
$addresses->execute([$userId]);
$addresses = $addresses->fetchAll();

// Presentation is kept in views/cart/checkout.view.php.
$pageKey = 'cart/checkout';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/cart/checkout.view.php';
require_once __DIR__ . '/../../includes/footer.php';
