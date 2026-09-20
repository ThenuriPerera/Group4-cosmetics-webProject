<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$productId = (int)($_GET['product_id'] ?? $_POST['product_id'] ?? 0);
$userId = (int)current_user()['user_id'];

$error = '';
$success = '';

/*
|--------------------------------------------------------------------------
| Validate Product
|--------------------------------------------------------------------------
*/

if ($productId <= 0) {
    http_response_code(400);
    exit('Invalid product.');
}

/*
|--------------------------------------------------------------------------
| Did this customer purchase the product?
|--------------------------------------------------------------------------
*/

$purchaseCheck = $pdo->prepare(
    "SELECT COUNT(*)
     FROM Order_Item oi
     JOIN `Order` o ON oi.order_id = o.order_id
     WHERE o.user_id = ?
       AND oi.product_id = ?
       AND o.order_status != 'Cancelled'"
);

$purchaseCheck->execute([
    $userId,
    $productId
]);

$hasPurchased = (int)$purchaseCheck->fetchColumn() > 0;


/*
|--------------------------------------------------------------------------
| Submit / Update Review
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$hasPurchased) {

        $error = 'You can only review products you have purchased.';

    } else {

        $action = $_POST['action'] ?? 'create';

        /*
        |--------------------------------------------------------------------------
        | DELETE REVIEW
        |--------------------------------------------------------------------------
        */

        if ($action === 'delete') {

            $reviewId = (int)($_POST['review_id'] ?? 0);

            if ($reviewId > 0) {

                $delete = $pdo->prepare(
                    "DELETE FROM Review
                     WHERE review_id = ?
                       AND user_id = ?
                       AND product_id = ?"
                );

                $delete->execute([
                    $reviewId,
                    $userId,
                    $productId
                ]);

                $success = 'Your review has been deleted.';
            }

        }

        /*
        |--------------------------------------------------------------------------
        | CREATE / UPDATE REVIEW
        |--------------------------------------------------------------------------
        */

        else {

            $rating = (int)($_POST['rating'] ?? 0);
            $comment = trim($_POST['comment'] ?? '');

            /*
            | Validate rating
            */

            if ($rating < 1 || $rating > 5) {

                $error = 'Please select a rating from 1 to 5.';

            } elseif ($comment === '') {

                $error = 'Please write a comment.';

            } elseif (strlen($comment) < 5) {

                $error = 'Your review must contain at least 5 characters.';

            } else {

                $reviewId = (int)($_POST['review_id'] ?? 0);

                /*
                |--------------------------------------------------------------------------
                | UPDATE EXISTING REVIEW
                |--------------------------------------------------------------------------
                */

                if ($reviewId > 0) {

                    $update = $pdo->prepare(
                         "UPDATE Review
                             SET rating = ?,
                             comment = ?,
                             status = 'Pending'
                             WHERE review_id = ?
                             AND user_id = ?
                             AND product_id = ?"
                    );

                    $update->execute([
                        $rating,
                        $comment,
                        $reviewId,
                        $userId,
                        $productId
                    ]);

                         $success = 'Your review has been updated and is awaiting re-approval.';

                }

                /*
                |--------------------------------------------------------------------------
                | CREATE NEW REVIEW
                |--------------------------------------------------------------------------
                */

                else {

                    /*
                    | Prevent duplicate reviews
                    */

                    $existingCheck = $pdo->prepare(
                        "SELECT review_id
                         FROM Review
                         WHERE user_id = ?
                           AND product_id = ?
                         LIMIT 1"
                    );

                    $existingCheck->execute([
                        $userId,
                        $productId
                    ]);

                    $existingReview = $existingCheck->fetch(PDO::FETCH_ASSOC);

                    if ($existingReview) {

                        $error = 'You have already reviewed this product. You can edit your existing review.';

                    } else {
                        /*
                        | Review enters moderation queue as Pending.
                        */

                        $insert = $pdo->prepare(
                            "INSERT INTO Review
                                (user_id, product_id, rating, comment, status)
                             VALUES
                                (?, ?, ?, ?, 'Pending')"
                        );

                        $insert->execute([
                            $userId,
                            $productId,
                            $rating,
                            $comment
                        ]);

                         $success = 'Your review has been submitted and is awaiting admin approval.';

                    }
                }
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Customer's Existing Review
|--------------------------------------------------------------------------
*/

$myReviewStmt = $pdo->prepare(
    "SELECT *
     FROM Review
     WHERE user_id = ?
       AND product_id = ?
     LIMIT 1"
);

$myReviewStmt->execute([
    $userId,
    $productId
]);

$myReview = $myReviewStmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Product Reviews
|--------------------------------------------------------------------------
|
| | Only Approved reviews are shown to customers.
|
*/

$stmt = $pdo->prepare(
    "SELECT
        r.*,
        u.name
     FROM Review r
     JOIN `User` u
       ON r.user_id = u.user_id
     WHERE r.product_id = ?
       AND r.status = 'Approved'
     ORDER BY r.review_id DESC"
);

$stmt->execute([
    $productId
]);

$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Smart Rating Summary
|--------------------------------------------------------------------------
*/

$ratingStmt = $pdo->prepare(
    "SELECT
        COUNT(*) AS total_reviews,
        COALESCE(AVG(rating), 0) AS average_rating,
        SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) AS five_star,
        SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) AS four_star,
        SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) AS three_star,
        SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) AS two_star,
        SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) AS one_star
     FROM Review
     WHERE product_id = ?
       AND status = 'Approved'"
);

$ratingStmt->execute([
    $productId
]);

$ratingSummary = $ratingStmt->fetch(PDO::FETCH_ASSOC);


/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageKey = 'orders/reviews';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/orders/reviews.view.php';

require_once __DIR__ . '/../../includes/footer.php';