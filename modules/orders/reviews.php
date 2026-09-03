<?php
/**
 * MODULE OWNER: Member 4 (Order Tracking, Reviews, Wishlist, Admin Analytics)
 * Section 5.7 - Reviews & Ratings
 * TODO (Member 4):
 *  - Verify the user actually purchased this product before allowing a review
 *    (join Order_Item + Order where user_id matches)
 *  - New reviews should default to status='Pending' until admin approves
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();
   $user = current_user();
   $userId = (int)$user['user_id'];
  
$productId = $_GET['product_id'] ?? $_POST['product_id'] ?? null;
$productId = (int)$productId;

   /*
 * Validate product ID
 */
if ($productId <= 0) {
    header('Location: /modules/products/product.php');
    exit;
}

$message = '';
$messageType = '';


/*
 * Handle review submission
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

     /*
     * Validate rating
     */
    if ($rating < 1 || $rating > 5) {

        $message = 'Please select a rating between 1 and 5.';
        $messageType = 'error';

    } elseif ($comment === '') {

        $message = 'Please enter your review comment.';
        $messageType = 'error';

    } else {
        /*
         * Check whether the customer purchased this product.
         *
         * A non-cancelled order containing this product
         * is considered a purchase.
         */
        $purchaseStmt = $pdo->prepare("
            SELECT COUNT(*)
            FROM `Order` o
            INNER JOIN Order_Item oi
                ON o.order_id = oi.order_id
            WHERE o.user_id = ?
              AND oi.product_id = ?
              AND o.order_status != 'Cancelled'
        ");

        $purchaseStmt->execute([$userId, $productId]);

        $hasPurchased = (int)$purchaseStmt->fetchColumn() > 0;

        if (!$hasPurchased) {

            $message = 'You can only review products that you have purchased.';
            $messageType = 'error';

        } else {

            /*
             * Check whether the customer already reviewed
             * this product.
             */
            $duplicateStmt = $pdo->prepare("
                SELECT COUNT(*)
                FROM Review
                WHERE user_id = ?
                  AND product_id = ?
            ");

            $duplicateStmt->execute([$userId, $productId]);

            $alreadyReviewed = (int)$duplicateStmt->fetchColumn() > 0;

            if ($alreadyReviewed) {

                $message = 'You have already submitted a review for this product.';
                $messageType = 'error';

            } else {

                /*
                 * New reviews are Pending until admin approval.
                 */
                $insertStmt = $pdo->prepare("
                    INSERT INTO Review
                    (user_id, product_id, rating, comment, status)
                    VALUES (?, ?, ?, ?, 'Pending')
                ");

                $insertStmt->execute([
                    $userId,
                    $productId,
                    $rating,
                    $comment
                ]);

                $message = 'Your review has been submitted and is waiting for admin approval.';
                $messageType = 'success';
            }
        }
    }
}

    // TODO: purchase verification before insert
    $pdo->prepare(
        "INSERT INTO Review (user_id, product_id, rating, comment, status) VALUES (?, ?, ?, ?, 'Pending')"
    )->execute([$userId, $productId, $rating, $comment]);

    header('Location: /modules/products/product.php?id=' . $productId);
    exit;
}

$stmt = $pdo->prepare("SELECT r.*, u.name FROM Review r JOIN User u ON r.user_id = u.user_id WHERE product_id = ? AND status = 'Approved'");
$stmt->execute([$productId]);
$reviews = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="reviews">
    <h2>Reviews</h2>
    <?php foreach ($reviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['name']) ?></strong> — <?= $r['rating'] ?>/5
            <p><?= htmlspecialchars($r['comment']) ?></p>
        </div>
    <?php endforeach; ?>

    <form method="post">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId) ?>">
        <label>Rating
            <select name="rating">
                <option value="5">5</option><option value="4">4</option>
                <option value="3">3</option><option value="2">2</option><option value="1">1</option>
            </select>
        </label>
        <textarea name="comment" placeholder="Your review..."></textarea>
        <button type="submit">Submit Review</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
