<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * TODO (Member 2):
 *  - Implement 3-level filtering (Category -> Subcategory -> Product type, Section 5.8)
 *  - Add search box
 *  - Pull in Product_Variant for shade/size options on listing
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$categoryId = $_GET['category_id'] ?? null;

if ($categoryId) {
    $stmt = $pdo->prepare("SELECT * FROM Product WHERE category_id = ?");
    $stmt->execute([$categoryId]);
} else {
    $stmt = $pdo->query("SELECT * FROM Product ORDER BY product_id DESC");
}
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM Category")->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="catalogue">
    <h1>Shop All Products</h1>

    <div class="filter-bar">
        <!-- TODO: build out Level 1/2/3 filter tree from Section 5.8 -->
        <a href="/modules/products/index.php">All</a>
        <?php foreach ($categories as $cat): ?>
            <a href="?category_id=<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($p['image'] ?: '/assets/images/placeholder.png') ?>" alt="<?= htmlspecialchars($p['product_name']) ?>">
                <h3><?= htmlspecialchars($p['product_name']) ?></h3>
                <p>Rs. <?= number_format($p['price'], 2) ?></p>
                <a href="/modules/products/product.php?id=<?= $p['product_id'] ?>">View</a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <p>No products yet — add some via the Editor panel.</p>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
