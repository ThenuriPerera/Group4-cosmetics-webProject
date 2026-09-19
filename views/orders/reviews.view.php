
<?php

if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}

?>

<style>

/* =========================================================
   REVIEWS PAGE
========================================================= */

.reviews {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
}

.reviews h2 {
    margin-bottom: 25px;
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
    letter-spacing: 2px;
}

.review-text {
    margin: 15px 0 8px;
    line-height: 1.6;
    word-break: break-word;
}

.review small {
    color: #888;
}


/* =========================================================
   REVIEW ACTIONS
========================================================= */

.review-actions {
    margin-top: 12px;
}

.delete-review-form {
    display: inline;
}

.delete-review-button {
    border: 1px solid #d88 !important;
    background: #fff !important;
    color: #b33 !important;
    padding: 7px 13px !important;
    border-radius: 15px !important;
    cursor: pointer;
    font-size: 12px;
}

.delete-review-button:hover {
    background: #b33 !important;
    color: #fff !important;
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

.review-comment p {
    margin: 8px 0;
    line-height: 1.5;
    word-break: break-word;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
}

.delete-comment-form {
    display: inline;
}

.delete-comment-button {
    border: 1px solid #d88 !important;
    background: #fff !important;
    color: #b33 !important;
    padding: 6px 12px !important;
    border-radius: 15px !important;
    cursor: pointer;
    font-size: 12px;
}

.delete-comment-button:hover {
    background: #b33 !important;
    color: #fff !important;
}


/* =========================================================
   COMMENT FORM
========================================================= */

.comment-form {
    margin-top: 20px;
}

.comment-form textarea,
.add-review textarea {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    margin: 8px 0 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-family: inherit;
    resize: vertical;
}

.comment-form textarea {
    min-height: 80px;
}


/* =========================================================
   ADD REVIEW
========================================================= */

.add-review {
    margin-top: 35px;
    padding: 25px;
    background: #f8f5f6;
    border-radius: 16px;
}

.add-review h3 {
    margin-top: 0;
}

.add-review label {
    display: block;
    margin-top: 15px;
}

.add-review textarea {
    min-height: 120px;
}


/* =========================================================
   STAR RATING
========================================================= */

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
    margin: 10px 0 5px;
}

.star-rating input {
    display: none;
}

.star-rating label {
    font-size: 40px;
    color: #ccc;
    cursor: pointer;
    transition: color 0.2s ease;
    margin: 0;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #d49a24;
}

.rating-text {
    margin: 5px 0 20px;
    color: #777;
    font-size: 14px;
}


/* =========================================================
   GENERAL BUTTONS
========================================================= */

.reviews button {
    border: none;
    padding: 10px 18px;
    border-radius: 20px;
    cursor: pointer;
    background: #222;
    color: #fff;
}

.reviews button:hover {
    opacity: 0.9;
}


/* =========================================================
   MESSAGES
========================================================= */

.error {
    background: #fdecec;
    color: #a33;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.success {
    background: #eaf7ed;
    color: #28733b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 600px) {

    .reviews {
        padding: 15px;
        margin: 20px auto;
    }

    .review {
        padding: 18px;
    }

    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .star-rating label {
        font-size: 34px;
    }

    .comment-header {
        align-items: flex-start;
    }

}

</style>


<section class="reviews">

    <h2>Customer Reviews</h2>


    <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

    <?php if (!empty($error)): ?>

        <p class="error">
            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    <?php endif; ?>


    <!-- =====================================================
         COMMENT ERROR
    ====================================================== -->

    <?php if (!empty($commentError)): ?>

        <p class="error">
            <?= htmlspecialchars(
                $commentError,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

    <?php endif; ?>


    <!-- =====================================================
         REVIEW SUBMITTED
    ====================================================== -->

    <?php if (isset($_GET['review_submitted'])): ?>

        <p class="success">
            Your review has been submitted successfully.
        </p>

    <?php endif; ?>


    <!-- =====================================================
         REVIEW DELETED
    ====================================================== -->

    <?php if (isset($_GET['review_deleted'])): ?>

        <p class="success">
            Your review and rating have been deleted successfully.
        </p>

    <?php endif; ?>


    <!-- =====================================================
         COMMENT SUBMITTED
    ====================================================== -->

    <?php if (isset($_GET['comment_submitted'])): ?>

        <p class="success">
            Your comment has been posted successfully.
        </p>

    <?php endif; ?>


    <!-- =====================================================
         COMMENT DELETED
    ====================================================== -->

    <?php if (isset($_GET['comment_deleted'])): ?>

        <p class="success">
            Your comment has been deleted successfully.
        </p>

    <?php endif; ?>


    <!-- =====================================================
         EXISTING REVIEWS
    ====================================================== -->

    <?php if (empty($reviews)): ?>

        <p>
            No approved reviews yet.
            Be the first to review this product!
        </p>

    <?php else: ?>


        <?php foreach ($reviews as $r): ?>

            <article class="review">


                <!-- =================================================
                     REVIEW HEADER
                ================================================== -->

                <div class="review-header">

                    <strong>
                        <?= htmlspecialchars(
                            $r['name'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </strong>


                    <span
                        class="review-rating"
                        aria-label="<?= (int) $r['rating'] ?> out of 5 stars"
                    >
                        <?= str_repeat(
                            '★',
                            (int) $r['rating']
                        ) ?><?= str_repeat(
                            '☆',
                            5 - (int) $r['rating']
                        ) ?>
                    </span>

                </div>


                <!-- =================================================
                     REVIEW TEXT
                ================================================== -->

                <p class="review-text">

                    <?= nl2br(
                        htmlspecialchars(
                            $r['comment'],
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
                        $r['rating_date'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </small>


                <!-- =================================================
                     DELETE OWN REVIEW
                ================================================== -->

                <?php if (
                    (int) $r['user_id'] === (int) $userId
                ): ?>

                    <div class="review-actions">

                        <form
                            method="post"
                            class="delete-review-form"
                            onsubmit="return confirm('Are you sure you want to delete this review and rating?');"
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
                                value="<?= (int) $productId ?>"
                            >

                            <input
                                type="hidden"
                                name="review_id"
                                value="<?= (int) $r['review_id'] ?>"
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
                                Delete My Review
                            </button>

                        </form>

                    </div>

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
                                $r['review_id']
                            ]
                        )
                    ): ?>


                        <?php foreach (
                            $reviewComments[
                                $r['review_id']
                            ] as $c
                        ): ?>

                            <div class="review-comment">


                                <!-- COMMENT HEADER -->

                                <div class="comment-header">

                                    <strong>

                                        <?= htmlspecialchars(
                                            $c['name'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </strong>


                                    <!-- DELETE OWN COMMENT -->

                                    <?php if (
                                        (int) $c['user_id']
                                        === (int) $userId
                                    ): ?>

                                        <form
                                            method="post"
                                            class="delete-comment-form"
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');"
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
                                                value="<?= (int) $productId ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="comment_id"
                                                value="<?= (int) $c['comment_id'] ?>"
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
                                            $c['comment'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )
                                    ) ?>

                                </p>


                                <!-- COMMENT DATE -->

                                <small>

                                    <?= htmlspecialchars(
                                        $c['comment_date'],
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

                    <form
                        method="post"
                        class="comment-form"
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
                            value="<?= (int) $productId ?>"
                        >

                        <input
                            type="hidden"
                            name="review_id"
                            value="<?= (int) $r['review_id'] ?>"
                        >

                        <input
                            type="hidden"
                            name="action"
                            value="submit_comment"
                        >

                        <textarea
                            name="review_comment"
                            placeholder="Write a comment..."
                            maxlength="2000"
                            required
                        ></textarea>

                        <button type="submit">
                            💬 Post Comment
                        </button>

                    </form>


                </div>


            </article>

        <?php endforeach; ?>


    <?php endif; ?>


    <!-- =========================================================
         ADD REVIEW
    ========================================================== -->

    <?php if ($hasPurchased): ?>

        <div class="add-review">

            <h3>
                Write Your Review
            </h3>


            <form method="post">

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
                    value="<?= (int) $productId ?>"
                >

                <input
                    type="hidden"
                    name="action"
                    value="submit_review"
                >


                <!-- =================================================
                     STAR RATING
                ================================================== -->

                <label>
                    Rating
                </label>


                <div class="star-rating">

                    <input
                        type="radio"
                        id="star5"
                        name="rating"
                        value="5"
                        required
                    >

                    <label
                        for="star5"
                        title="5 stars"
                    >
                        ★
                    </label>


                    <input
                        type="radio"
                        id="star4"
                        name="rating"
                        value="4"
                    >

                    <label
                        for="star4"
                        title="4 stars"
                    >
                        ★
                    </label>


                    <input
                        type="radio"
                        id="star3"
                        name="rating"
                        value="3"
                    >

                    <label
                        for="star3"
                        title="3 stars"
                    >
                        ★
                    </label>


                    <input
                        type="radio"
                        id="star2"
                        name="rating"
                        value="2"
                    >

                    <label
                        for="star2"
                        title="2 stars"
                    >
                        ★
                    </label>


                    <input
                        type="radio"
                        id="star1"
                        name="rating"
                        value="1"
                    >

                    <label
                        for="star1"
                        title="1 star"
                    >
                        ★
                    </label>

                </div>


                <p class="rating-text">
                    Click a star to select your rating.
                </p>


                <!-- =================================================
                     REVIEW TEXT
                ================================================== -->

                <label>

                    Your review

                    <textarea
                        name="comment"
                        placeholder="Tell us about your experience..."
                        maxlength="5000"
                        required
                    ></textarea>

                </label>


                <!-- =================================================
                     SUBMIT REVIEW
                ================================================== -->

                <button type="submit">
                    ⭐ Submit Review
                </button>


                <p>

                    <small>
                        Your review will appear immediately.
                    </small>

                </p>


            </form>

        </div>


    <?php else: ?>


        <p>
            Only customers who have purchased this product
            can leave a review.
        </p>


    <?php endif; ?>


</section>
