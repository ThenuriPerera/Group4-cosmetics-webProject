<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$productId = $_GET['product_id'] ?? $_POST['product_id'] ?? null;
$userId = current_user()['user_id'];
$error = '';

// Did this user actually buy the product?
$purchaseCheck = $pdo->prepare(
    "SELECT COUNT(*) FROM Order_Item oi
     JOIN `Order` o ON oi.order_id = o.order_id
     WHERE o.user_id = ? AND oi.product_id = ? AND o.order_status != 'Cancelled'"
);
$purchaseCheck->execute([$userId, $productId]);
$hasPurchased = $purchaseCheck->fetchColumn() > 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$hasPurchased) {
        $error = 'You can only review products you have purchased.';
    } else {
        $rating = (int)$_POST['rating'];
        $comment = trim($_POST['comment']);

        $pdo->prepare(
            "INSERT INTO Review (user_id, product_id, rating, comment, status) VALUES (?, ?, ?, ?, 'Pending')"
        )->execute([$userId, $productId, $rating, $comment]);

        header('Location: ' . lg_url('/modules/products/product.php?id=') . $productId . '&review_submitted=1');
        exit;
    }
}

$stmt = $pdo->prepare("SELECT r.*, u.name FROM Review r JOIN User u ON r.user_id = u.user_id WHERE product_id = ? AND status = 'Approved'");
$stmt->execute([$productId]);
$reviews = $stmt->fetchAll();

// Presentation is kept in views/orders/reviews.view.php.
$pageKey = 'orders/reviews';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/orders/reviews.view.php';
require_once __DIR__ . '/../../includes/footer.php';
