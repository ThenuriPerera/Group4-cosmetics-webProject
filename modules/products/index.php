<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Status: COMPLETE — 3-level filter (Category -> Sub-category -> Product type, Section 5.8)
 * plus a simple search box.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$categoryId  = $_GET['category_id'] ?? null;
$subCategory = $_GET['sub_category'] ?? null;
$productType = $_GET['product_type'] ?? null;
$search      = trim($_GET['q'] ?? '');

$sql = "SELECT * FROM Product WHERE 1=1";
$params = [];

if ($categoryId) { $sql .= " AND category_id = ?"; $params[] = $categoryId; }
if ($subCategory) { $sql .= " AND sub_category = ?"; $params[] = $subCategory; }
if ($productType) { $sql .= " AND product_type = ?"; $params[] = $productType; }
if ($search !== '') { $sql .= " AND product_name LIKE ?"; $params[] = "%$search%"; }

$sql .= " ORDER BY product_id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM Category")->fetchAll();

// Build Level 2 (sub-categories) for the currently selected category
$subCategories = [];
if ($categoryId) {
    $stmt2 = $pdo->prepare("SELECT DISTINCT sub_category FROM Product WHERE category_id = ? AND sub_category IS NOT NULL AND sub_category != ''");
    $stmt2->execute([$categoryId]);
    $subCategories = $stmt2->fetchAll(PDO::FETCH_COLUMN);
}

// Build Level 3 (product types) for the currently selected sub-category
$productTypes = [];
if ($subCategory) {
    $stmt3 = $pdo->prepare("SELECT DISTINCT product_type FROM Product WHERE sub_category = ? AND product_type IS NOT NULL AND product_type != ''");
    $stmt3->execute([$subCategory]);
    $productTypes = $stmt3->fetchAll(PDO::FETCH_COLUMN);
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="catalogue">
    <h1>Shop All Products</h1>

    <form method="get" class="search-bar">
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search products...">
        <button type="submit">Search</button>
    </form>

    <div class="filter-tree">
        <!-- Level 1: Main Categories -->
        <div class="filter-level">
            <strong>Category</strong>
            <a href="/modules/products/index.php" class="<?= !$categoryId ? 'active' : '' ?>">All</a>
            <?php foreach ($categories as $cat): ?>
                <a href="?category_id=<?= $cat['category_id'] ?>" class="<?= $categoryId == $cat['category_id'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['category_name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Level 2: Sub-categories, only shown once a category is picked -->
        <?php if ($categoryId && $subCategories): ?>
            <div class="filter-level">
                <strong>Sub-Category</strong>
                <a href="?category_id=<?= $categoryId ?>" class="<?= !$subCategory ? 'active' : '' ?>">All</a>
                <?php foreach ($subCategories as $sc): ?>
                    <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($sc) ?>" class="<?= $subCategory === $sc ? 'active' : '' ?>">
                        <?= htmlspecialchars($sc) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Level 3: Product types, only shown once a sub-category is picked -->
        <?php if ($subCategory && $productTypes): ?>
            <div class="filter-level">
                <strong>Type</strong>
                <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($subCategory) ?>" class="<?= !$productType ? 'active' : '' ?>">All</a>
                <?php foreach ($productTypes as $pt): ?>
                    <a href="?category_id=<?= $categoryId ?>&sub_category=<?= urlencode($subCategory) ?>&product_type=<?= urlencode($pt) ?>" class="<?= $productType === $pt ? 'active' : '' ?>">
                        <?= htmlspecialchars($pt) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
            <p>No products match this filter.</p>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
