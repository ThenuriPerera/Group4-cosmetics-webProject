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

// Presentation is kept in views/products/index.view.php.
$pageKey = 'products/index';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/index.view.php';
require_once __DIR__ . '/../../includes/footer.php';
