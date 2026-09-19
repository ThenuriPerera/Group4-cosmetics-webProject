
<?php

if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}


/*
|--------------------------------------------------------------------------
| Logged-in User
|--------------------------------------------------------------------------
*/

$userId = is_logged_in()
    ? (int) current_user()['user_id']
    : 0;


/*
|--------------------------------------------------------------------------
| Product Data
|--------------------------------------------------------------------------
*/

$displayImage = lg_image_url($product);

$productId = (int) $product['product_id'];


/*
|--------------------------------------------------------------------------
| Wishlist Messages
|--------------------------------------------------------------------------
*/

$wishlistMessage =
    $_SESSION['wishlist_message'] ?? '';

$wishlistError =
    $_SESSION['wishlist_error'] ?? '';

unset($_SESSION['wishlist_message']);
unset($_SESSION['wishlist_error']);

?>

<style>

/* =========================================================
   PRODUCT DETAILS
========================================================= */

.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 50px;
}

.product-detail img {
    width: 100%;
    max-height: 500px;
    object-fit: contain;
}

.image-missing {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f7f3f5;
    color: #999;
}

.product-detail .price {
    font-size: 25px;
    font-weight: bold;
}


/* =========================================================
   REVIEWS
========================================================= */

.reviews {
    max-width: 950px;
    margin: 40px auto;
    padding: 20px;
}

.review-summary {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 20px;
    background: #f8f5f6;
    border-radius: 15px;
    margin-bottom: 25px;
}

.review-summary strong {
    font-size: 30px;
}

.review-summary span {
    color: #d49a24;
    font-size: 20px;
}

.review-summary small {
    color: #777;
}


/* =========================================================
   REVIEW CARD
========================================================= */

.review {
    padding: 25px;
    border: 1px solid #eee;
    border-radius: 16px;
    margin-bottom: 20px;
    background: #fff;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.review-rating {
    color: #d49a24;
    font-size: 18px;
}

.review-text {
    margin: 15px 0 8px;
    line-height: 1.6;
}

.review > small {
    color: #888;
}


/* =========================================================
   DELETE REVIEW
========================================================= */

.delete-review-form {
    margin-top: 12px;
}

.delete-review-button {
    padding: 7px 14px;
    border: 1px solid #d88;
    border-radius: 15px;
    background: #fff;
    color: #b33;
    cursor: pointer;
    font-size: 12px;
}

.delete-review-button:hover {
    background: #b33;
    color: #fff;
}


/* =========================================================
   COMMENTS
========================================================= */

.review-comments {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.review-comments h4 {
    margin-bottom: 15px;
}

.review-comment {
    background: #f8f5f6;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 12px;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.review-comment p {
    margin: 8px 0;
    line-height: 1.5;
}

.review-comment small {
    color: #888;
}


/* =========================================================
   DELETE COMMENT
========================================================= */

.delete-comment-form {
    display: inline;
}

.delete-comment-button {
    padding: 6px 12px;
    border: 1px solid #d88;
    border-radius: 15px;
    background: #fff;
    color: #b33;
    cursor: pointer;
    font-size: 12px;
}

.delete-comment-button:hover {
    background: #b33;
    color: #fff;
}


/* =========================================================
   COMMENT FORM
========================================================= */

.comment-form {
    margin-top: 20px;
}

.comment-form textarea {
    width: 100%;
    min-height: 80px;
    box-sizing: border-box;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-family: inherit;
    resize: vertical;
}

.comment-form button {
    margin-top: 10px;
    padding: 10px 18px;
    border: none;
    border-radius: 20px;
    background: #222;
    color: #fff;
    cursor: pointer;
}

.comment-form button:hover {
    opacity: 0.85;
}


/* =========================================================
   WRITE REVIEW
========================================================= */

.write-review {
    margin-top: 35px;
    padding: 25px;
    background: #f8f5f6;
    border-radius: 16px;
}

.write-review .button {
    display: inline-block;
    padding: 12px 22px;
    background: #222;
    color: #fff;
    text-decoration: none;
    border-radius: 25px;
}


/* =========================================================
   PRODUCT FORMS
========================================================= */

.product-detail form {
    margin-top: 15px;
}

.product-detail button {
    padding: 10px 18px;
    border: none;
    border-radius: 20px;
    background: #222;
    color: #fff;
    cursor: pointer;
}

.product-detail select,
.product-detail input[type="number"] {
    padding: 8px;
    margin: 5px;
    border: 1px solid #ddd;
    border-radius: 8px;
}


/* =========================================================
   MESSAGES
========================================================= */

.success {
    background: #eaf7ed;
    color: #28733b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.error {
    background: #fdecec;
    color: #a33;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 750px) {

    .product-detail {
        grid-template-columns: 1fr;
    }

    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-summary {
        flex-direction: column;
        align-items: flex-start;
    }

}

</style>


<!-- =============================================================
     PRODUCT DETAILS
============================================================== -->

<section class="product-detail">


    <!-- =========================================================
         WISHLIST MESSAGE
    ========================================================== -->

    <?php if ($wishlistMessage): ?>

        <p class="success">

            <?= htmlspecialchars(
                $wishlistMessage,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

            <a
                href="<?= htmlspecialchars(
                    lg_url('/modules/orders/wishlist.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >
                View wishlist
            </a>

        </p>

    <?php endif; ?>


    <?php if ($wishlistError): ?>

        <p class="error">

            <?= htmlspecialchars(
                $wishlistError,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </p>

    <?php endif; ?>


    <!-- =========================================================
         PRODUCT IMAGE
    ========================================================== -->

    <div>

        <?php if ($displayImage !== ''): ?>

            <img
                src="<?= htmlspecialchars(
                    $displayImage,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                alt="<?= htmlspecialchars(
                    $product['product_name'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

        <?php else: ?>

            <div class="image-missing">

                Product photo coming soon

            </div>

        <?php endif; ?>

    </div>


    <!-- =========================================================
         PRODUCT INFORMATION
    ========================================================== -->

    <div>

        <h2>

            <?= htmlspecialchars(
                $product['product_name'],
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </h2>


        <?php if ($avgRating !== null): ?>

            <p>

                Rating:

                <?= number_format(
                    $avgRating,
                    1
                ) ?>

                / 5

                (<?= count($reviews) ?> reviews)

            </p>

        <?php endif; ?>


        <p class="price">

            Rs.

            <?= number_format(
                (float) $product['price'],
                2
            ) ?>

        </p>


        <p>

            <?= nl2br(
                htmlspecialchars(
                    $product['description'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                )
            ) ?>

        </p>


        <!-- =====================================================
             LOGGED IN CUSTOMER
        ====================================================== -->

        <?php if (is_logged_in()): ?>


            <!-- =================================================
                 ADD TO CART
            ================================================== -->

            <form
                method="post"
                action="<?= htmlspecialchars(
                    lg_url('/modules/cart/cart.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $productId ?>"
                >


                <?php if (!empty($variants)): ?>

                    <label>

                        Variant

                        <select name="variant_id">

                            <?php foreach (
                                $variants as $variant
                            ): ?>

                                <option
                                    value="<?= (int) $variant['variant_id'] ?>"
                                >

                                    <?= htmlspecialchars(
                                        trim(
                                            ($variant['shade'] ?? '') .
                                            ' ' .
                                            ($variant['size'] ?? '')
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                    —

                                    Rs.

                                    <?= number_format(
                                        (float) (
                                            $variant['price']
                                            ?: $product['price']
                                        ),
                                        2
                                    ) ?>

                                    (

                                    <?= (int) $variant['stock'] ?>

                                    in stock

                                    )

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </label>

                <?php endif; ?>


                <label>

                    Quantity

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                    >

                </label>


                <button
                    type="submit"
                    name="add_to_cart"
                >

                    Add to Cart

                </button>

            </form>


            <!-- =================================================
                 ADD TO WISHLIST
            ================================================== -->

            <form
                method="post"
                action="<?= htmlspecialchars(
                    lg_url('/modules/orders/wishlist.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        csrf_token(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= $productId ?>"
                >

                <button
                    type="submit"
                    name="add_wishlist"
                    value="1"
                >

                    ♡ Add to Wishlist

                </button>

            </form>


        <?php else: ?>


            <p>

                <a
                    href="<?= htmlspecialchars(
                        lg_url('/modules/auth/login.php'),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >
                    Login
                </a>

                to add this product to your wishlist.

            </p>


        <?php endif; ?>


    </div>

</section>


<!-- =============================================================
     CUSTOMER REVIEWS
============================================================== -->

<section class="reviews">


    <h2>

        Customer Reviews

    </h2>


    <!-- =========================================================
         SUCCESS MESSAGES
    ========================================================== -->

    <?php if (isset($_GET['comment_submitted'])): ?>

        <p class="success">

            Your comment has been posted successfully.

        </p>

    <?php endif; ?>


    <?php if (isset($_GET['comment_deleted'])): ?>

        <p class="success">

            Your comment has been deleted successfully.

        </p>

    <?php endif; ?>


    <?php if (isset($_GET['review_submitted'])): ?>

        <p class="success">

            Your review has been submitted successfully.

        </p>

    <?php endif; ?>


    <?php if (isset($_GET['review_deleted'])): ?>

        <p class="success">

            Your review and rating have been deleted successfully.

        </p>

    <?php endif; ?>


    <!-- =========================================================
         REVIEW SUMMARY
    ========================================================== -->

    <?php if ($avgRating !== null): ?>

        <div class="review-summary">

            <strong>

                <?= number_format(
                    $avgRating,
                    1
                ) ?>/5

            </strong>


            <span>

                <?= str_repeat(
                    '★',
                    (int) round($avgRating)
                ) ?>

                <?= str_repeat(
                    '☆',
                    5 - (int) round($avgRating)
                ) ?>

            </span>


            <small>

                Based on

                <?= count($reviews) ?>

                approved review(s)

            </small>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         REVIEWS
    ========================================================== -->

    <?php if (empty($reviews)): ?>


        <p>

            No reviews yet.
            Be the first customer to share your experience!

        </p>


    <?php else: ?>


        <?php foreach ($reviews as $review): ?>


            <article class="review">


                <!-- =================================================
                     REVIEW HEADER
                ================================================== -->

                <div class="review-header">

                    <strong>

                        <?= htmlspecialchars(
                            $review['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </strong>


                    <span class="review-rating">

                        <?= str_repeat(
                            '★',
                            (int) $review['rating']
                        ) ?>

                        <?= str_repeat(
                            '☆',
                            5 - (int) $review['rating']
                        ) ?>

                    </span>

                </div>


                <!-- =================================================
                     REVIEW TEXT
                ================================================== -->

                <p class="review-text">

                    <?= nl2br(
                        htmlspecialchars(
                            $review['comment'],
                            ENT_QUOTES,
                            'UTF-8'
                        )
                    ) ?>

                </p>


                <!-- =================================================
                     REVIEW DATE
                ================================================== -->

                <small>

                    <?= htmlspecialchars(
                        $review['rating_date'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </small>


                <!-- =================================================
                     DELETE OWN REVIEW
                ================================================== -->

                <?php if (
                    (int) $review['user_id']
                    === (int) $userId
                ): ?>


                    <form
                        method="post"
                        class="delete-review-form"
                        action="<?= htmlspecialchars(
                            lg_url('/modules/orders/reviews.php'),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        onsubmit="return confirm('Are you sure you want to delete this review and rating?');"
                    >

                        <input
                            type="hidden"
                            name="product_id"
                            value="<?= $productId ?>"
                        >

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= htmlspecialchars(
                                csrf_token(),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                        <input
                            type="hidden"
                            name="review_id"
                            value="<?= (int) $review['review_id'] ?>"
                        >

                        <input
                            type="hidden"
                            name="action"
                            value="delete_review"
                        >

                        <button
                            type="submit"
                            class="delete-review-button"
                        >

                            🗑️ Delete Review

                        </button>

                    </form>


                <?php endif; ?>


                <!-- =================================================
                     COMMENTS
                ================================================== -->

                <div class="review-comments">

                    <h4>

                        💬 Comments

                    </h4>


                    <?php if (
                        !empty(
                            $reviewComments[
                                $review['review_id']
                            ]
                        )
                    ): ?>


                        <?php foreach (
                            $reviewComments[
                                $review['review_id']
                            ] as $comment
                        ): ?>


                            <div class="review-comment">


                                <!-- COMMENT HEADER -->

                                <div class="comment-header">

                                    <strong>

                                        <?= htmlspecialchars(
                                            $comment['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </strong>


                                    <!-- DELETE OWN COMMENT -->

                                    <?php if (
                                        (int) $comment['user_id']
                                        === (int) $userId
                                    ): ?>


                                        <form
                                            method="post"
                                            class="delete-comment-form"
                                            action="<?= htmlspecialchars(
                                                lg_url('/modules/orders/reviews.php'),
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');"
                                        >

                                            <input
                                                type="hidden"
                                                name="product_id"
                                                value="<?= $productId ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(
                                                    csrf_token(),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="comment_id"
                                                value="<?= (int) $comment['comment_id'] ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="delete_comment"
                                            >

                                            <button
                                                type="submit"
                                                class="delete-comment-button"
                                            >

                                                Delete

                                            </button>

                                        </form>


                                    <?php endif; ?>

                                </div>


                                <!-- COMMENT TEXT -->

                                <p>

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $comment['comment'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )
                                    ) ?>

                                </p>


                                <!-- COMMENT DATE -->

                                <small>

                                    <?= htmlspecialchars(
                                        $comment['comment_date'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </small>


                            </div>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <p>

                            No comments yet.

                        </p>


                    <?php endif; ?>


                    <!-- =================================================
                         ADD COMMENT
                    ================================================== -->

                    <?php if (is_logged_in()): ?>


                        <form
                            method="post"
                            class="comment-form"
                            action="<?= htmlspecialchars(
                                lg_url('/modules/orders/reviews.php'),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?= $productId ?>"
                            >

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars(
                                    csrf_token(),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                            >

                            <input
                                type="hidden"
                                name="review_id"
                                value="<?= (int) $review['review_id'] ?>"
                            >

                            <input
                                type="hidden"
                                name="action"
                                value="submit_comment"
                            >

                            <textarea
                                name="review_comment"
                                placeholder="Write a comment on this review..."
                                required
                            ></textarea>

                            <button type="submit">

                                💬 Post Comment

                            </button>

                        </form>


                    <?php endif; ?>


                </div>


            </article>


        <?php endforeach; ?>


    <?php endif; ?>


    <!-- =========================================================
         WRITE REVIEW
    ========================================================== -->

    <?php if (is_logged_in()): ?>


        <div class="write-review">

            <h3>

                Share Your Experience

            </h3>


            <p>

                Have you purchased this product?
                Share your experience with other customers.

            </p>


            <a
                class="button"
                href="<?= htmlspecialchars(
                    lg_url(
                        '/modules/orders/reviews.php?product_id='
                        . $productId
                    ),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                ⭐ Write a Review

            </a>

        </div>


    <?php else: ?>


        <p>

            <a
                href="<?= htmlspecialchars(
                    lg_url('/modules/auth/login.php'),
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

                Login

            </a>

            to write a review or comment.

        </p>


    <?php endif; ?>


</section>
