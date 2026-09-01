<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * TODO (Member 2):
 *  - Show Product_Variant options (shade/size selector)
 *  - Pull in approved Reviews (Member 4's table) for display
 *  - "Add to Wishlist" button (guest -> prompt to login, Section 3.1)
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

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="product-detail">
    <img src="<?= htmlspecialchars($product['image'] ?: '/assets/images/placeholder.png') ?>" alt="">
    <div>
        <h1><?= htmlspecialchars($product['product_name']) ?></h1>
        <p class="price">Rs. <?= number_format($product['price'], 2) ?></p>
        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <?php if (is_logged_in()): ?>
            <form method="post" action="/modules/cart/cart.php">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <label>Qty <input type="number" name="quantity" value="1" min="1"></label>
                <button type="submit" name="add_to_cart">Add to Cart</button>
            </form>
        <?php else: ?>
            <p><a href="/modules/auth/login.php">Login</a> to add this to your cart.</p>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
