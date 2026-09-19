
<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../config/paths.php';

require_login();

$userId = (int) current_user()['user_id'];

$productId = (int) (
    $_POST['product_id']
    ?? $_GET['product_id']
    ?? 0
);


/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function reviews_redirect($url)
{
    header('Location: ' . $url);
    exit;
}


/*
|--------------------------------------------------------------------------
| Validate Product
|--------------------------------------------------------------------------
*/

if ($productId <= 0) {

    $_SESSION['review_error'] =
        'Invalid product selected.';

    reviews_redirect(
        lg_url('/modules/products/index.php')
    );
}


/*
|--------------------------------------------------------------------------
| CSRF Protection
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_csrf_token();
}


/*
|--------------------------------------------------------------------------
| Check Customer Purchased Product
|--------------------------------------------------------------------------
*/

$purchaseCheck = $pdo->prepare(
    "SELECT oi.order_item_id
     FROM Order_Item AS oi
     INNER JOIN `Order` AS o
         ON o.order_id = oi.order_id
     WHERE o.user_id = ?
       AND oi.product_id = ?
       AND o.order_status <> 'Cancelled'
     LIMIT 1"
);

$purchaseCheck->execute([
    $userId,
    $productId
]);

$hasPurchased = (bool) $purchaseCheck->fetch();


/*
|--------------------------------------------------------------------------
| POST ACTION
|--------------------------------------------------------------------------
*/

$action = $_POST['action'] ?? '';


/*
|--------------------------------------------------------------------------
| SUBMIT REVIEW
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $action === 'submit_review'
) {

    if (!$hasPurchased) {

        $_SESSION['review_error'] =
            'You can review this product only after purchasing it.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    $rating = (int) (
        $_POST['rating'] ?? 0
    );

    $comment = trim(
        $_POST['comment'] ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | Validate Rating
    |--------------------------------------------------------------------------
    */

    if ($rating < 1 || $rating > 5) {

        $_SESSION['review_error'] =
            'Please select a rating from 1 to 5 stars.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Validate Review
    |--------------------------------------------------------------------------
    */

    if ($comment === '') {

        $_SESSION['review_error'] =
            'Please write a review before submitting.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Insert Review
    |--------------------------------------------------------------------------
    */

    try {

        $insertReview = $pdo->prepare(
            "INSERT INTO Review
                (
                    user_id,
                    product_id,
                    rating,
                    comment,
                    status
                )
             VALUES (?, ?, ?, ?, 'Approved')"
        );

        $insertReview->execute([
            $userId,
            $productId,
            $rating,
            $comment
        ]);

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId .
                '&review_submitted=1'
            )
        );

    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['review_error'] =
            'Your review could not be added.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }
}


/*
|--------------------------------------------------------------------------
| DELETE OWN REVIEW
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $action === 'delete_review'
) {

    $reviewId = (int) (
        $_POST['review_id'] ?? 0
    );


    if ($reviewId <= 0) {

        $_SESSION['review_error'] =
            'Invalid review selected.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    try {

        /*
        | Delete only the logged-in customer's own review.
        |
        | Review_Comment has ON DELETE CASCADE,
        | so comments belonging to this review are
        | automatically deleted by MySQL.
        */

        $deleteReview = $pdo->prepare(
            "DELETE FROM Review
             WHERE review_id = ?
               AND user_id = ?
               AND product_id = ?"
        );

        $deleteReview->execute([
            $reviewId,
            $userId,
            $productId
        ]);


        if ($deleteReview->rowCount() > 0) {

            reviews_redirect(
                lg_url(
                    '/modules/products/product.php?id=' .
                    $productId .
                    '&review_deleted=1'
                )
            );

        } else {

            $_SESSION['review_error'] =
                'You can delete only your own review.';

            reviews_redirect(
                lg_url(
                    '/modules/products/product.php?id=' .
                    $productId
                )
            );
        }

    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['review_error'] =
            'The review could not be deleted.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }
}


/*
|--------------------------------------------------------------------------
| SUBMIT COMMENT
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $action === 'submit_comment'
) {

    $reviewId = (int) (
        $_POST['review_id'] ?? 0
    );

    /*
    | The view uses review_comment.
    */

    $comment = trim(
        $_POST['review_comment']
        ?? $_POST['comment']
        ?? ''
    );


    if ($reviewId <= 0) {

        $_SESSION['review_error'] =
            'Invalid review selected.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    if ($comment === '') {

        $_SESSION['review_error'] =
            'Please write a comment.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Check Review Exists
    |--------------------------------------------------------------------------
    */

    $reviewCheck = $pdo->prepare(
        "SELECT review_id
         FROM Review
         WHERE review_id = ?
           AND product_id = ?
           AND status = 'Approved'
         LIMIT 1"
    );

    $reviewCheck->execute([
        $reviewId,
        $productId
    ]);


    if (!$reviewCheck->fetch()) {

        $_SESSION['review_error'] =
            'This review is no longer available.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Insert Comment
    |--------------------------------------------------------------------------
    */

    try {

        $insertComment = $pdo->prepare(
            "INSERT INTO Review_Comment
                (
                    review_id,
                    user_id,
                    comment,
                    status
                )
             VALUES (?, ?, ?, 'Approved')"
        );

        $insertComment->execute([
            $reviewId,
            $userId,
            $comment
        ]);

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId .
                '&comment_submitted=1'
            )
        );

    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['review_error'] =
            'Your comment could not be added.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }
}


/*
|--------------------------------------------------------------------------
| DELETE OWN COMMENT
|--------------------------------------------------------------------------
*/

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $action === 'delete_comment'
) {

    $commentId = (int) (
        $_POST['comment_id'] ?? 0
    );


    if ($commentId <= 0) {

        $_SESSION['review_error'] =
            'Invalid comment selected.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }


    try {

        /*
        | Delete only the logged-in customer's own comment.
        */

        $deleteComment = $pdo->prepare(
            "DELETE FROM Review_Comment
             WHERE comment_id = ?
               AND user_id = ?"
        );

        $deleteComment->execute([
            $commentId,
            $userId
        ]);


        if ($deleteComment->rowCount() > 0) {

            reviews_redirect(
                lg_url(
                    '/modules/products/product.php?id=' .
                    $productId .
                    '&comment_deleted=1'
                )
            );

        } else {

            $_SESSION['review_error'] =
                'You can delete only your own comment.';

            reviews_redirect(
                lg_url(
                    '/modules/products/product.php?id=' .
                    $productId
                )
            );
        }

    } catch (PDOException $e) {

        error_log($e->getMessage());

        $_SESSION['review_error'] =
            'The comment could not be deleted.';

        reviews_redirect(
            lg_url(
                '/modules/products/product.php?id=' .
                $productId
            )
        );
    }
}


/*
|--------------------------------------------------------------------------
| LOAD APPROVED REVIEWS
|--------------------------------------------------------------------------
*/

$reviewStmt = $pdo->prepare(
    "SELECT
        r.review_id,
        r.user_id,
        r.product_id,
        r.rating,
        r.comment,
        r.rating_date,
        u.name
     FROM Review AS r
     INNER JOIN User AS u
         ON u.user_id = r.user_id
     WHERE r.product_id = ?
       AND r.status = 'Approved'
     ORDER BY r.rating_date DESC"
);

$reviewStmt->execute([
    $productId
]);

$reviews = $reviewStmt->fetchAll();


/*
|--------------------------------------------------------------------------
| LOAD APPROVED COMMENTS
|--------------------------------------------------------------------------
*/

$reviewComments = [];

if (!empty($reviews)) {

    $reviewIds = array_column(
        $reviews,
        'review_id'
    );

    $placeholders = implode(
        ',',
        array_fill(
            0,
            count($reviewIds),
            '?'
        )
    );


    $commentStmt = $pdo->prepare(
        "SELECT
            rc.comment_id,
            rc.review_id,
            rc.user_id,
            rc.comment,
            rc.comment_date,
            u.name
         FROM Review_Comment AS rc
         INNER JOIN User AS u
             ON u.user_id = rc.user_id
         WHERE rc.review_id IN ($placeholders)
           AND rc.status = 'Approved'
         ORDER BY rc.comment_date ASC"
    );


    $commentStmt->execute(
        $reviewIds
    );


    foreach (
        $commentStmt->fetchAll()
        as $comment
    ) {

        $reviewComments[
            (int) $comment['review_id']
        ][] = $comment;
    }
}


/*
|--------------------------------------------------------------------------
| Review Statistics
|--------------------------------------------------------------------------
*/

$reviewCount = count($reviews);

$averageRating = 0;

if ($reviewCount > 0) {

    $totalRating = 0;

    foreach ($reviews as $review) {

        $totalRating += (int) $review['rating'];
    }

    $averageRating =
        $totalRating / $reviewCount;
}


/*
|--------------------------------------------------------------------------
| Compatibility variables
|--------------------------------------------------------------------------
*/

$commentsByReview = $reviewComments;


/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageKey = 'orders/reviews';

require_once __DIR__ .
    '/../../includes/header.php';

require_once __DIR__ .
    '/../../views/orders/reviews.view.php';

require_once __DIR__ .
    '/../../includes/footer.php';
