<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Status: COMPLETE — Editor/Admin can add, edit, delete products (Section 3.3).
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['editor', 'admin']);

$message = '';

// Add or update product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $id = $_POST['product_id'] ?? null;
    $name = trim($_POST['product_name']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $categoryId = $_POST['category_id'];
    $brandId = $_POST['brand_id'];
    $description = trim($_POST['description']);
    $skinTone = $_POST['skin_tone'] ?: null;
    $skinType = $_POST['skin_type'] ?: null;
    $image = trim($_POST['image']);
    $subCategory = trim($_POST['sub_category']);
    $productType = trim($_POST['product_type']);

    if ($id) {
        $pdo->prepare(
            "UPDATE Product SET product_name=?, price=?, stock=?, category_id=?, brand_id=?, description=?, skin_tone=?, skin_type=?, image=?, sub_category=?, product_type=?
             WHERE product_id=?"
        )->execute([$name, $price, $stock, $categoryId, $brandId, $description, $skinTone, $skinType, $image, $subCategory, $productType, $id]);
        $message = 'Product updated.';
    } else {
        $pdo->prepare(
            "INSERT INTO Product (product_name, price, stock, category_id, brand_id, description, skin_tone, skin_type, image, sub_category, product_type)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        )->execute([$name, $price, $stock, $categoryId, $brandId, $description, $skinTone, $skinType, $image, $subCategory, $productType]);
        $message = 'Product added.';
    }
}

// Delete product
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM Product WHERE product_id = ?")->execute([$_GET['delete']]);
    header('Location: /modules/products/manage.php');
    exit;
}

$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM Product WHERE product_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM Category")->fetchAll();
$brands = $pdo->query("SELECT * FROM Brand")->fetchAll();
$products = $pdo->query("SELECT p.*, c.category_name FROM Product p LEFT JOIN Category c ON p.category_id = c.category_id ORDER BY p.product_id DESC")->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="admin-products">
    <h1>Manage Products</h1>
    <?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>

    <h2><?= $editProduct ? 'Edit Product' : 'Add New Product' ?></h2>
    <form method="post">
        <?php if ($editProduct): ?>
            <input type="hidden" name="product_id" value="<?= $editProduct['product_id'] ?>">
        <?php endif; ?>
        <label>Name <input type="text" name="product_name" value="<?= htmlspecialchars($editProduct['product_name'] ?? '') ?>" required></label>
        <label>Price <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($editProduct['price'] ?? '') ?>" required></label>
        <label>Stock <input type="number" name="stock" value="<?= htmlspecialchars($editProduct['stock'] ?? 0) ?>" required></label>
        <label>Category
            <select name="category_id" required>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['category_id'] ?>" <?= ($editProduct['category_id'] ?? '') == $c['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Brand
            <select name="brand_id">
                <?php foreach ($brands as $b): ?>
                    <option value="<?= $b['brand_id'] ?>" <?= ($editProduct['brand_id'] ?? '') == $b['brand_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['brand_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Skin Tone (for Find Your Shade)
            <select name="skin_tone">
                <option value="">— none —</option>
                <?php foreach (['fair','light','medium','tan','deep'] as $tone): ?>
                    <option value="<?= $tone ?>" <?= ($editProduct['skin_tone'] ?? '') === $tone ? 'selected' : '' ?>><?= ucfirst($tone) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Skin Type (for Skin Type Recommender)
            <select name="skin_type">
                <option value="">— none —</option>
                <?php foreach (['oily','dry','combination','normal'] as $type): ?>
                    <option value="<?= $type ?>" <?= ($editProduct['skin_type'] ?? '') === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Sub-Category (Level 2, e.g. "Face", "Cleansers") <input type="text" name="sub_category" value="<?= htmlspecialchars($editProduct['sub_category'] ?? '') ?>"></label>
        <label>Product Type (Level 3, e.g. "Foundation", "Gel Cleanser") <input type="text" name="product_type" value="<?= htmlspecialchars($editProduct['product_type'] ?? '') ?>"></label>
        <label>Image URL <input type="text" name="image" value="<?= htmlspecialchars($editProduct['image'] ?? '') ?>" placeholder="/assets/images/product.jpg"></label>
        <label>Description <textarea name="description"><?= htmlspecialchars($editProduct['description'] ?? '') ?></textarea></label>
        <button type="submit" name="save_product"><?= $editProduct ? 'Update' : 'Add' ?> Product</button>
        <?php if ($editProduct): ?><a href="/modules/products/manage.php">Cancel</a><?php endif; ?>
    </form>

    <h2>All Products</h2>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['product_name']) ?></td>
                <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                <td>Rs. <?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <a href="?edit=<?= $p['product_id'] ?>">Edit</a> |
                    <a href="?delete=<?= $p['product_id'] ?>" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
