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

        header('Location: /modules/products/product.php?id=' . $productId . '&review_submitted=1');
        exit;
    }
}

$stmt = $pdo->prepare("SELECT r.*, u.name FROM Review r JOIN User u ON r.user_id = u.user_id WHERE product_id = ? AND status = 'Approved'");
$stmt->execute([$productId]);
$reviews = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="reviews">
    <h2>Reviews</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <?php foreach ($reviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['name']) ?></strong> — <?= $r['rating'] ?>/5
            <p><?= htmlspecialchars($r['comment']) ?></p>
        </div>
    <?php endforeach; ?>

    <?php if ($hasPurchased): ?>
        <form method="post">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId) ?>">
            <label>Rating
                <select name="rating">
                    <option value="5">5</option><option value="4">4</option>
                    <option value="3">3</option><option value="2">2</option><option value="1">1</option>
                </select>
            </label>
            <textarea name="comment" placeholder="Your review..." required></textarea>
            <button type="submit">Submit Review</button>
            <p><small>Your review will appear after admin approval.</small></p>
        </form>
    <?php else: ?>
        <p>Only customers who have purchased this product can leave a review.</p>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
