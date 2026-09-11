<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Section 3.3 — Editors can organize and maintain category-related content.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['editor', 'admin']);

$message = '';
$error = '';

// Load category being edited (before POST handling)
$editCategory = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM Category WHERE category_id = ?");
    $stmt->execute([$_GET['edit']]);
    $editCategory = $stmt->fetch();
}

// Add or update category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    $id   = $_POST['category_id'] ?? null;
    $name = trim($_POST['category_name']);
    $desc = trim($_POST['category_description']);

    if ($name === '') {
        $error = 'Category name is required.';
    } else {
        if ($id) {
            $pdo->prepare(
                "UPDATE Category SET category_name = ?, category_description = ? WHERE category_id = ?"
            )->execute([$name, $desc, $id]);
            $message = 'Category updated.';
        } else {
            $pdo->prepare(
                "INSERT INTO Category (category_name, category_description) VALUES (?, ?)"
            )->execute([$name, $desc]);
            $message = 'Category added.';
        }
    }
}

// Delete category (with safety check)
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];

    // Safety: block deletion if products reference this category
    $check = $pdo->prepare("SELECT COUNT(*) FROM Product WHERE category_id = ?");
    $check->execute([$deleteId]);
    $productCount = (int)$check->fetchColumn();

    if ($productCount > 0) {
        $error = "Cannot delete — $productCount product(s) still use this category. Reassign them first.";
    } else {
        $pdo->prepare("DELETE FROM Category WHERE category_id = ?")->execute([$deleteId]);
        header('Location: ' . lg_url('/modules/products/categories.php'));
        exit;
    }
}

// Load all categories with product counts
$categories = $pdo->query("
    SELECT c.*, 
           (SELECT COUNT(*) FROM Product p WHERE p.category_id = c.category_id) AS product_count
    FROM Category c
    ORDER BY c.category_name ASC
")->fetchAll();

// Presentation
$pageKey = 'products/categories';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/categories.view.php';
require_once __DIR__ . '/../../includes/footer.php';