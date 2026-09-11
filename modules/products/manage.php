<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Status: COMPLETE — Editor/Admin can add, edit, delete products (Section 3.3).
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['editor', 'admin']);

$message = '';

// ============================================================
// Load edit product FIRST (so POST handling can use its data)
// ============================================================
$editProduct = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM Product WHERE product_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editProduct = $stmt->fetch();
}

// ============================================================
// Add or update product
// ============================================================
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
    $subCategory = trim($_POST['sub_category']);
    $productType = trim($_POST['product_type']);

    // ----- Image Upload Handling -----
    // Keep existing image on edit if no new file uploaded
    $image = $editProduct['image'] ?? null;

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/images/products/';
        $fileExt = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($fileExt, $allowed)) {
            // Unique filename to prevent overwrites
            $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
                // Delete old image if this is an edit
                if (!empty($editProduct['image']) && file_exists($uploadDir . $editProduct['image'])) {
                    unlink($uploadDir . $editProduct['image']);
                }
                $image = $newFileName;
            } else {
                $message = 'Failed to save uploaded image.';
            }
        } else {
            $message = 'Invalid image format. Only JPG, PNG, WEBP, and GIF are allowed.';
        }
    }

    // ----- Save to Database -----
    if ($id) {
        $pdo->prepare(
            "UPDATE Product SET product_name=?, price=?, stock=?, category_id=?, brand_id=?, description=?, skin_tone=?, skin_type=?, image=?, sub_category=?, product_type=?
             WHERE product_id=?"
        )->execute([$name, $price, $stock, $categoryId, $brandId, $description, $skinTone, $skinType, $image, $subCategory, $productType, $id]);
        $message = $message ?: 'Product updated.';
    } else {
        $pdo->prepare(
            "INSERT INTO Product (product_name, price, stock, category_id, brand_id, description, skin_tone, skin_type, image, sub_category, product_type)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        )->execute([$name, $price, $stock, $categoryId, $brandId, $description, $skinTone, $skinType, $image, $subCategory, $productType]);
        $message = $message ?: 'Product added.';
    }
}

// ============================================================
// Delete product
// ============================================================
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM Product WHERE product_id = ?")->execute([$_GET['delete']]);
    header('Location: ' . lg_url('/modules/products/manage.php'));
    exit;
}

// ============================================================
// Load data for the view
// ============================================================
$categories = $pdo->query("SELECT * FROM Category")->fetchAll();
$brands = $pdo->query("SELECT * FROM Brand")->fetchAll();
$products = $pdo->query("SELECT p.*, c.category_name FROM Product p LEFT JOIN Category c ON p.category_id = c.category_id ORDER BY p.product_id DESC")->fetchAll();

// Presentation is kept in views/products/manage.view.php.
$pageKey = 'products/manage';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/manage.view.php';
require_once __DIR__ . '/../../includes/footer.php';
