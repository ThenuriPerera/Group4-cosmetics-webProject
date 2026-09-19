<?php

/**
 * Lumine Glow - Product Details
 * Loads product information, variants,
 * approved reviews and approved review comments.
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$id = $_GET['id'] ?? 0;


/*
|--------------------------------------------------------------------------
| Load product
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT *
     FROM Product
     WHERE product_id = ?"
);

$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    die('Product not found.');
}


/*
|--------------------------------------------------------------------------
| Load product variants
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT *
     FROM Product_Variant
     WHERE product_id = ?"
);

$stmt->execute([$id]);

$variants = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Load approved reviews
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT
        r.*,
        u.name
     FROM Review AS r
     INNER JOIN `User` AS u
        ON u.user_id = r.user_id
     WHERE r.product_id = ?
       AND r.status = 'Approved'
     ORDER BY r.rating_date DESC"
);

$stmt->execute([$id]);

$reviews = $stmt->fetchAll();


/*
|--------------------------------------------------------------------------
| Calculate average rating
|--------------------------------------------------------------------------
*/

$avgRating = $reviews
    ? array_sum(array_column($reviews, 'rating')) / count($reviews)
    : null;


/*
|--------------------------------------------------------------------------
| Load approved comments for reviews
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
            rc.*,
            u.name
         FROM Review_Comment AS rc
         INNER JOIN `User` AS u
            ON u.user_id = rc.user_id
         WHERE rc.review_id IN ($placeholders)
           AND rc.status = 'Approved'
         ORDER BY rc.comment_date ASC"
    );

    $commentStmt->execute($reviewIds);

    $comments = $commentStmt->fetchAll();

    foreach ($comments as $comment) {

        $reviewComments[
            $comment['review_id']
        ][] = $comment;
    }
}


/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

$pageKey = 'products/product';

require_once __DIR__ . '/../../includes/header.php';

require __DIR__ . '/../../views/products/product.view.php';

require_once __DIR__ . '/../../includes/footer.php';