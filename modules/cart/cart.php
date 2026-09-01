<?php
/**
 * MODULE OWNER: Member 3 (Cart, Checkout, Payment, Promo Codes)
 * Section 5.4 - Shopping Cart
 * TODO (Member 3):
 *  - Replace full-page reload with AJAX (fetch) for quantity update/remove
 *  - Merge guest-session cart into DB cart on login (optional, or block guests)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];

// Ensure the user has a cart row
$stmt = $pdo->prepare("SELECT * FROM Cart WHERE user_id = ?");
$stmt->execute([$userId]);
$cart = $stmt->fetch();
if (!$cart) {
    $pdo->prepare("INSERT INTO Cart (user_id) VALUES (?)")->execute([$userId]);
    $cartId = $pdo->lastInsertId();
} else {
    $cartId = $cart['cart_id'];
}

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $productId = $_POST['product_id'];
    $qty = max(1, (int)($_POST['quantity'] ?? 1));

    // TODO: check Product_Variant stock before allowing add
    $existing = $pdo->prepare("SELECT * FROM Cart_Item WHERE cart_id = ? AND product_id = ?");
    $existing->execute([$cartId, $productId]);
    $row = $existing->fetch();

    if ($row) {
        $pdo->prepare("UPDATE Cart_Item SET quantity = quantity + ? WHERE cart_item_id = ?")
            ->execute([$qty, $row['cart_item_id']]);
    } else {
        $pdo->prepare("INSERT INTO Cart_Item (cart_id, product_id, quantity) VALUES (?, ?, ?)")
            ->execute([$cartId, $productId, $qty]);
    }
    header('Location: /modules/cart/cart.php');
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

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="cart-page">
    <h1>Your Cart</h1>
    <table class="cart-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php foreach ($cartItems as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>Rs. <?= number_format($item['price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($cartItems)): ?>
            <tr><td colspan="4">Your cart is empty.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <p class="cart-total">Total: Rs. <?= number_format($total, 2) ?></p>
    <a class="btn" href="/modules/cart/checkout.php">Proceed to Checkout</a>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
