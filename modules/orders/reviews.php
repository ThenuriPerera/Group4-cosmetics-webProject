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
/*
 * Get approved reviews for this product
 */
$reviewStmt = $pdo->prepare("
    SELECT
        r.review_id,
        r.rating,
        r.comment,
        r.rating_date,
        u.name
    FROM Review r
    INNER JOIN `User` u
        ON r.user_id = u.user_id
    WHERE r.product_id = ?
      AND r.status = 'Approved'
    ORDER BY r.rating_date DESC
");

$reviewStmt->execute([$productId]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

/*
 * Get product information
 */
$productStmt = $pdo->prepare("
    SELECT product_id, product_name
    FROM Product
    WHERE product_id = ?
");

$productStmt->execute([$productId]);
$product = $productStmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: /modules/products/product.php');
    exit;
}

require_once __DIR__ . '/../../includes/header.php';
?>
<style>
    .reviews-section {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .reviews-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .reviews-header h2 {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .reviews-header p {
        color: #777;
        margin: 0;
    }

    .message {
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .message.success {
        background: #e8f7ed;
        color: #267a43;
        border: 1px solid #b9e5c6;
    }

    .message.error {
        background: #fdeaea;
        color: #b42318;
        border: 1px solid #f2b8b5;
    }

    .review-form {
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 35px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
    }

    .review-form h3 {
        margin-top: 0;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .rating-select,
    .review-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        box-sizing: border-box;
    }

    .review-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .rating-select:focus,
    .review-textarea:focus {
        outline: none;
        border-color: #999;
    }

    .submit-review-btn {
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .review-card {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
    }

    .review-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }

    .review-user {
        font-weight: 700;
    }

    .review-rating {
        font-weight: 600;
    }

    .review-date {
        color: #888;
        font-size: 13px;
    }

    .review-comment {
        color: #444;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .no-reviews {
        text-align: center;
        padding: 30px;
        background: #fafafa;
        border-radius: 10px;
        color: #777;
    }
</style>
<section class="reviews-section">

    <div class="reviews-header">
        <h2>Customer Reviews</h2>

        <p>
            Reviews for
            <strong><?= htmlspecialchars($product['product_name']) ?></strong>
        </p>
    </div>

    <?php if ($message !== ''): ?>
        <div class="message <?= htmlspecialchars($messageType) ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <!-- Review Form -->
    <div class="review-form">

        <h3>Write a Review</h3>

        <form method="post">

            <input
                type="hidden"
                name="product_id"
                value="<?= htmlspecialchars($productId) ?>"
            >

            <div class="form-group">

                <label for="rating">
                    Rating
                </label>

                <select
                    id="rating"
                    name="rating"
                    class="rating-select"
                    required
                >
                    <option value="">Select rating</option>
                    <option value="5">★★★★★ - 5</option>
                    <option value="4">★★★★☆ - 4</option>
                    <option value="3">★★★☆☆ - 3</option>
                    <option value="2">★★☆☆☆ - 2</option>
                    <option value="1">★☆☆☆☆ - 1</option>
                </select>

            </div>


            <div class="form-group">

                <label for="comment">
                    Your Review
                </label>

                <textarea
                    id="comment"
                    name="comment"
                    class="review-textarea"
                    placeholder="Share your experience with this product..."
                    maxlength="1000"
                    required
                ></textarea>

            </div>


            <button
                type="submit"
                class="submit-review-btn"
            >
                Submit Review
            </button>

        </form>

    </div>
    <!-- Approved Reviews -->
    <div class="reviews-list">

        <?php if (empty($reviews)): ?>

            <div class="no-reviews">
                <p>No approved reviews yet.</p>
            </div>

        <?php else: ?>

            <?php foreach ($reviews as $review): ?>

                <div class="review-card">

                    <div class="review-top">

                        <div>
                            <div class="review-user">
                                <?= htmlspecialchars($review['name']) ?>
                            </div>

                            <div class="review-date">
                                <?= htmlspecialchars($review['rating_date']) ?>
                            </div>
                        </div>

                        <div class="review-rating">
                            <?= str_repeat('★', (int)$review['rating']) ?>
                            <?= str_repeat('☆', 5 - (int)$review['rating']) ?>
                        </div>

                    </div>

                    <p class="review-comment">
                        <?= htmlspecialchars($review['comment']) ?>
                    </p>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    