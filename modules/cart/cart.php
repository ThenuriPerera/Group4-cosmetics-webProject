<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];

$stmt = $pdo->prepare("SELECT * FROM Cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cart = $stmt->fetch();
if (!$cart) {
    $pdo->prepare("INSERT INTO Cart (user_id) VALUES (?)")->execute([$userId]);
    $cartId = $pdo->lastInsertId();
} else {
    $cartId = $cart['cart_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $variantId = $_POST['variant_id'] ?? null;
    $qty = max(1, (int)($_POST['quantity'] ?? 1));

    $existing = $pdo->prepare("SELECT * FROM Cart_Item WHERE cart_id = ? AND product_id = ? AND (variant_id <=> ?)");
    $existing->execute([$cartId, $productId, $variantId]);
    $row = $existing->fetch();

    if ($row) {
        $pdo->prepare("UPDATE Cart_Item SET quantity = quantity + ? WHERE cart_item_id = ?")
            ->execute([$qty, $row['cart_item_id']]);
    } else {
        $pdo->prepare("INSERT INTO Cart_Item (cart_id, product_id, variant_id, quantity) VALUES (?, ?, ?, ?)")
            ->execute([$cartId, $productId, $variantId, $qty]);
    }
    header('Location: ' . lg_url('/modules/cart/cart.php'));
    exit;
}

// Non-JS fallback: plain form remove/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $pdo->prepare("DELETE FROM Cart_Item WHERE cart_item_id = ? AND cart_id = ?")
        ->execute([$_POST['cart_item_id'], $cartId]);
    header('Location: ' . lg_url('/modules/cart/cart.php'));
    exit;
}

$items = $pdo->prepare(
    "SELECT ci.*, p.product_name, p.price, p.image
     FROM Cart_Item ci JOIN Product p ON ci.product_id = p.product_id
     WHERE ci.cart_id = ?"
);
$items->execute([$cartId]);
$cartItems = $items->fetchAll();

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Presentation is kept in views/cart/cart.view.php.
$pageKey = 'cart/cart';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/cart/cart.view.php';
require_once __DIR__ . '/../../includes/footer.php';
