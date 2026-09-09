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
    header('Location: /modules/cart/cart.php');
    exit;
}

// Non-JS fallback: plain form remove/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $pdo->prepare("DELETE FROM Cart_Item WHERE cart_item_id = ? AND cart_id = ?")
        ->execute([$_POST['cart_item_id'], $cartId]);
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
    <table class="cart-table" id="cart-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($cartItems as $item): ?>
            <tr data-cart-item-id="<?= $item['cart_item_id'] ?>" data-price="<?= $item['price'] ?>">
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>Rs. <?= number_format($item['price'], 2) ?></td>
                <td>
                    <input type="number" class="qty-input" min="1" value="<?= $item['quantity'] ?>" data-cart-item-id="<?= $item['cart_item_id'] ?>">
                </td>
                <td class="row-subtotal">Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                <td>
                    <button type="button" class="remove-btn" data-cart-item-id="<?= $item['cart_item_id'] ?>">Remove</button>
                    <!-- Non-JS fallback -->
                    <noscript>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                            <button type="submit" name="remove_item">Remove</button>
                        </form>
                    </noscript>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($cartItems)): ?>
            <tr><td colspan="5">Your cart is empty.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <p class="cart-total">Total: Rs. <span id="cart-total-amount"><?= number_format($total, 2) ?></span></p>
    <a class="btn" href="/modules/cart/checkout.php">Proceed to Checkout</a>
</section>

<script>
document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', () => {
        const id = input.dataset.cartItemId;
        const qty = Math.max(1, parseInt(input.value || '1', 10));
        fetch('/modules/cart/cart-ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=update_quantity&cart_item_id=${id}&quantity=${qty}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = input.closest('tr');
                const price = parseFloat(row.dataset.price);
                row.querySelector('.row-subtotal').textContent = 'Rs. ' + (price * qty).toFixed(2);
                document.getElementById('cart-total-amount').textContent = data.cart_total;
            }
        });
    });
});

document.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.cartItemId;
        fetch('/modules/cart/cart-ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=remove&cart_item_id=${id}`
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                btn.closest('tr').remove();
                document.getElementById('cart-total-amount').textContent = data.cart_total;
            }
        });
    });
});
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
