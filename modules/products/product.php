<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Status: COMPLETE — Product_Variant (shade/size) selector, wishlist button,
 * and approved reviews shown on the page.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM Product WHERE product_id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    die('Product not found.');
}

$variants = $pdo->prepare("SELECT * FROM Product_Variant WHERE product_id = ?");
$variants->execute([$id]);
$variants = $variants->fetchAll();

$reviews = $pdo->prepare(
    "SELECT r.*, u.name FROM Review r JOIN User u ON r.user_id = u.user_id WHERE product_id = ? AND status = 'Approved' ORDER BY rating_date DESC"
);
$reviews->execute([$id]);
$reviews = $reviews->fetchAll();
$avgRating = $reviews ? array_sum(array_column($reviews, 'rating')) / count($reviews) : null;

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="product-detail">
    <img src="<?= htmlspecialchars($product['image'] ?: '/assets/images/placeholder.png') ?>" alt="">
    <div>
        <h1><?= htmlspecialchars($product['product_name']) ?></h1>
        <?php if ($avgRating): ?><p>Rating: <?= number_format($avgRating, 1) ?> / 5 (<?= count($reviews) ?> reviews)</p><?php endif; ?>
        <p class="price">Rs. <?= number_format($product['price'], 2) ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if (is_logged_in()): ?>
            <form method="post" action="/modules/cart/cart.php">
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

            <form method="post" action="/modules/orders/wishlist.php">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <button type="submit" name="add_wishlist">Add to Wishlist</button>
            </form>
        <?php else: ?>
            <p><a href="/modules/auth/login.php">Login</a> to add this to your cart or wishlist.</p>
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
        <a href="/modules/orders/reviews.php?product_id=<?= $product['product_id'] ?>">Write a review</a>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
