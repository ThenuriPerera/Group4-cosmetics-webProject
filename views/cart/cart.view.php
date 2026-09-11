<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="cart-page">
    <p id="cart-feedback" role="status" hidden></p>

    <table class="cart-table" id="cart-table" data-endpoint="<?= htmlspecialchars(lg_url('modules/cart/cart-ajax.php')) ?>">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($cartItems as $item): ?>
            <tr data-cart-item-id="<?= $item['cart_item_id'] ?>" data-price="<?= $item['price'] ?>">
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>Rs. <?= number_format($item['price'], 2) ?></td>
                <td>
                    <input type="number" class="qty-input" aria-label="Quantity for <?= htmlspecialchars($item['product_name']) ?>" min="1" value="<?= $item['quantity'] ?>" data-cart-item-id="<?= $item['cart_item_id'] ?>">
                </td>
                <td class="row-subtotal">Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                <td>
                    <button type="button" class="remove-btn" data-cart-item-id="<?= $item['cart_item_id'] ?>">Remove</button>
                    <!-- Non-JS fallback -->
                    <noscript>
                        <form method="post" class="inline-form">
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
    <div class="cart-summary">
    <p class="cart-total">Total: Rs. <span id="cart-total-amount"><?= number_format($total, 2) ?></span></p>
    <a class="btn" id="checkout-link" <?= !$cartItems ? 'hidden' : '' ?> href="<?= htmlspecialchars(lg_url('modules/cart/checkout.php')) ?>">Continue to checkout</a>
    <a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Continue shopping</a>
    </div>
</section>
