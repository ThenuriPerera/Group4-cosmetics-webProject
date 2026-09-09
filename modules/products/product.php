<?php
/**
 * Product details, variants and approved reviews.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$id = $_GET['id'] ?? 0;

// Load product.
$stmt = $pdo->prepare(
    "SELECT * FROM Product WHERE product_id = ?"
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    die('Product not found.');
}

// Load product variants.
$stmt = $pdo->prepare(
    "SELECT * FROM Product_Variant WHERE product_id = ?"
);
$stmt->execute([$id]);
$variants = $stmt->fetchAll();

// Explicit table aliases prevent ambiguous column errors.
$stmt = $pdo->prepare(
    "SELECT r.*, u.name
     FROM Review AS r
     INNER JOIN `User` AS u ON u.user_id = r.user_id
     WHERE r.product_id = ?
       AND r.status = 'Approved'
     ORDER BY r.rating_date DESC"
);
$stmt->execute([$id]);
$reviews = $stmt->fetchAll();

$avgRating = $reviews
    ? array_sum(array_column($reviews, 'rating')) / count($reviews)
    : null;

$pageKey = 'products/product';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/product.view.php';
require_once __DIR__ . '/../../includes/footer.php';