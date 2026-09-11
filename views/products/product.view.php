<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="product-detail">
    <?php $displayImage = lg_image_url($product); ?>
    <?php if ($displayImage !== ''): ?>
        <img src="<?= htmlspecialchars($displayImage, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['product_name']) ?>">
    <?php else: ?><div class="image-missing">Product photo coming soon</div><?php endif; ?>
    <div>
        <h2><?= htmlspecialchars($product['product_name']) ?></h2>

        <?php if ($avgRating): ?><p>Rating: <?= number_format($avgRating, 1) ?> / 5 (<?= count($reviews) ?> reviews)</p><?php endif; ?>
        <p class="price">Rs. <?= number_format($product['price'], 2) ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if (is_logged_in()): ?>
            <form method="post" action="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/cart/cart.php">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">

                <?php if ($variants): ?>
                    <label>Variant
                        <select name="variant_id">
                            <?php foreach ($variants as $v): ?>
                                <option value="<?= $v['variant_id'] ?>">
                                    <?= htmlspecialchars(trim(($v['shade'] ?: '') . ' ' . ($v['size'] ?: ''))) ?>
                                    — Rs. <?= number_format($v['price'] ?: $product['price'], 2) ?>
                                    (<?= $v['stock'] ?> in stock)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                <?php endif; ?>

                <label>Qty <input type="number" name="quantity" value="1" min="1"></label>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>

            <form method="post" action="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/orders/wishlist.php">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <button type="submit" name="add_wishlist">Add to Wishlist</button>
            </form>
        <?php else: ?>
            <p><a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/auth/login.php">Login</a> to add this to your cart or wishlist.</p>
        <?php endif; ?>
    </div>
</section>

<section class="reviews">
    <h2>Reviews</h2>
    <?php foreach ($reviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['name']) ?></strong> — <?= $r['rating'] ?>/5
            <p><?= htmlspecialchars($r['comment']) ?></p>
        </div>
    <?php endforeach; ?>
    <?php if (empty($reviews)): ?><p>No reviews yet.</p><?php endif; ?>

    <?php if (is_logged_in()): ?>
        <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/orders/reviews.php?product_id=<?= $product['product_id'] ?>">Write a review</a>
    <?php endif; ?>
</section>
