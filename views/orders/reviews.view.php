<?php
if (!defined('LG_VIEW')) {
    http_response_code(404);
    exit;
}

$averageRating = (float)($ratingSummary['average_rating'] ?? 0);
$totalReviews = (int)($ratingSummary['total_reviews'] ?? 0);

$fiveStar  = (int)($ratingSummary['five_star'] ?? 0);
$fourStar  = (int)($ratingSummary['four_star'] ?? 0);
$threeStar = (int)($ratingSummary['three_star'] ?? 0);
$twoStar   = (int)($ratingSummary['two_star'] ?? 0);
$oneStar   = (int)($ratingSummary['one_star'] ?? 0);

function review_percentage($count, $total)
{
    if ($total <= 0) {
        return 0;
    }

    return round(($count / $total) * 100);
}

function review_stars($rating)
{
    $html = '';

    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $rating ? '★' : '☆';
    }

    return $html;
}
?>

<section class="smart-reviews">

    <!-- =========================================================
         HEADER
    ========================================================== -->

    <div class="review-header">

        <div>
            <span class="review-eyebrow">LUMINÉ GLOW</span>

            <h2>
                Smart Reviews
            </h2>

            <p>
                Real experiences from customers who purchased this product.
            </p>
        </div>

        <div class="review-live-badge">
            <span class="live-dot"></span>
            Reviews publish instantly
        </div>

    </div>


    <!-- =========================================================
         SUCCESS / ERROR
    ========================================================== -->

    <?php if (!empty($success)): ?>

        <div class="review-message success-message">
            <span>✓</span>
            <div>
                <strong>Review published!</strong>
                <p><?= htmlspecialchars($success) ?></p>
            </div>
        </div>

    <?php endif; ?>


    <?php if (!empty($error)): ?>

        <div class="review-message error-message">
            <span>!</span>
            <div>
                <strong>Review not submitted</strong>
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        </div>

    <?php endif; ?>


    <!-- =========================================================
         RATING SUMMARY
    ========================================================== -->

    <div class="rating-summary">

        <div class="rating-main">

            <div class="big-rating">
                <?= number_format($averageRating, 1) ?>
            </div>

            <div class="big-stars">
                <?= review_stars(round($averageRating)) ?>
            </div>

            <div class="review-count">
                <?= $totalReviews ?>
                <?= $totalReviews === 1 ? 'review' : 'reviews' ?>
            </div>

        </div>


        <div class="rating-bars">

            <?php
            $ratingRows = [
                5 => $fiveStar,
                4 => $fourStar,
                3 => $threeStar,
                2 => $twoStar,
                1 => $oneStar
            ];
            ?>

            <?php foreach ($ratingRows as $star => $count): ?>

                <?php
                $percentage = review_percentage($count, $totalReviews);
                ?>

                <div class="rating-row">

                    <span class="rating-label">
                        <?= $star ?> ★
                    </span>

                    <div class="rating-track">
                        <div
                            class="rating-fill"
                            style="width: <?= $percentage ?>%;"
                        ></div>
                    </div>

                    <span class="rating-number">
                        <?= $count ?>
                    </span>

                </div>

            <?php endforeach; ?>

        </div>

    </div>


    <!-- =========================================================
         CUSTOMER REVIEW FORM
    ========================================================== -->

    <?php if ($hasPurchased): ?>

        <div class="write-review-card">

            <div class="write-review-heading">

                <div>
                    <span class="mini-label">
                        VERIFIED CUSTOMER
                    </span>

                    <h3>
                        <?= $myReview ? 'Edit your review' : 'Share your experience' ?>
                    </h3>

                    <p>
                        Your honest experience helps other beauty lovers.
                    </p>
                </div>

                <div class="verified-icon">
                    ✓
                </div>

            </div>


            <form method="post" class="smart-review-form">

                <input
                    type="hidden"
                    name="product_id"
                    value="<?= htmlspecialchars($productId) ?>"
                >

                <?php if ($myReview): ?>

                    <input
                        type="hidden"
                        name="review_id"
                        value="<?= (int)$myReview['review_id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="action"
                        value="update"
                    >

                <?php endif; ?>


                <!-- STAR RATING -->

                <div class="star-rating-area">

                    <label>
                        Your rating
                    </label>

                    <div class="star-selector">

                        <?php for ($i = 5; $i >= 1; $i--): ?>

                            <input
                                type="radio"
                                id="star<?= $i ?>"
                                name="rating"
                                value="<?= $i ?>"
                                <?= ($myReview && (int)$myReview['rating'] === $i)
                                    ? 'checked'
                                    : (!$myReview && $i === 5 ? 'checked' : '') ?>
                                required
                            >

                            <label
                                for="star<?= $i ?>"
                                title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"
                            >
                                ★
                            </label>

                        <?php endfor; ?>

                    </div>

                    <div class="rating-hint">
                        Click the stars to rate your experience
                    </div>

                </div>


                <!-- SMART TAGS -->

                <div class="smart-tags">

                    <span>Quick experience tags:</span>

                    <button type="button" class="tag-btn" data-tag="Long Lasting">
                        ✨ Long Lasting
                    </button>

                    <button type="button" class="tag-btn" data-tag="Great Texture">
                        💧 Great Texture
                    </button>

                    <button type="button" class="tag-btn" data-tag="Good Value">
                        💰 Good Value
                    </button>

                    <button type="button" class="tag-btn" data-tag="Beautiful Finish">
                        💄 Beautiful Finish
                    </button>

                    <button type="button" class="tag-btn" data-tag="Highly Recommend">
                        ❤️ Highly Recommend
                    </button>

                </div>


                <!-- COMMENT -->

                <div class="comment-area">

                    <label for="review-comment">
                        Your review
                    </label>

                    <textarea
                        id="review-comment"
                        name="comment"
                        maxlength="1000"
                        placeholder="Tell us about the texture, finish, quality, packaging or your overall experience..."
                        required
                    ><?= htmlspecialchars($myReview['comment'] ?? '') ?></textarea>

                    <div class="character-counter">
                        <span id="reviewCharCount">0</span>/1000
                    </div>

                </div>


                <div class="review-form-bottom">

                    <div class="instant-note">
                        ⚡ Your review will be published instantly.
                    </div>

                    <button
                        type="submit"
                        class="submit-review-btn"
                    >
                        <?= $myReview ? 'Update Review' : 'Publish Review' ?>
                    </button>

                </div>

            </form>


            <!-- DELETE OWN REVIEW -->

            <?php if ($myReview): ?>

                <form method="post" class="delete-review-form">

                    <input
                        type="hidden"
                        name="product_id"
                        value="<?= htmlspecialchars($productId) ?>"
                    >

                    <input
                        type="hidden"
                        name="review_id"
                        value="<?= (int)$myReview['review_id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="action"
                        value="delete"
                    >

                    <button
                        type="submit"
                        class="delete-review-btn"
                        onclick="return confirm('Delete your review? This action cannot be undone.');"
                    >
                        🗑 Delete my review
                    </button>

                </form>

            <?php endif; ?>

        </div>

    <?php else: ?>

        <div class="purchase-review-message">

            <div class="lock-icon">
                🔒
            </div>

            <div>
                <strong>Purchase required</strong>

                <p>
                    Only customers who have purchased this product can leave
                    a review.
                </p>
            </div>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         REVIEW LIST
    ========================================================== -->

    <div class="review-list-header">

        <div>
            <span class="mini-label">
                CUSTOMER VOICES
            </span>

            <h3>
                What customers are saying
            </h3>
        </div>

        <span class="review-total-badge">
            <?= $totalReviews ?> total
        </span>

    </div>


    <?php if (!empty($reviews)): ?>

        <div class="review-list">

            <?php foreach ($reviews as $r): ?>

                <?php
                $reviewRating = (int)$r['rating'];

                $reviewComment = trim($r['comment']);

                $smartTag = '';

                if (
                    stripos($reviewComment, 'long lasting') !== false ||
                    stripos($reviewComment, 'last long') !== false
                ) {
                    $smartTag = '✨ Long Lasting';
                } elseif (
                    stripos($reviewComment, 'texture') !== false
                ) {
                    $smartTag = '💧 Great Texture';
                } elseif (
                    stripos($reviewComment, 'value') !== false ||
                    stripos($reviewComment, 'price') !== false
                ) {
                    $smartTag = '💰 Good Value';
                } elseif (
                    stripos($reviewComment, 'recommend') !== false
                ) {
                    $smartTag = '❤️ Highly Recommend';
                } elseif (
                    stripos($reviewComment, 'finish') !== false
                ) {
                    $smartTag = '💄 Beautiful Finish';
                }
                ?>

                <article class="review-card">

                    <div class="review-card-top">

                        <div class="customer-avatar">
                            <?= strtoupper(substr($r['name'], 0, 1)) ?>
                        </div>

                        <div class="customer-info">

                            <strong>
                                <?= htmlspecialchars($r['name']) ?>
                            </strong>

                            <span>
                                ✓ Verified Purchase
                            </span>

                        </div>

                        <div class="customer-rating">

                            <div class="review-stars">
                                <?= review_stars($reviewRating) ?>
                            </div>

                            <small>
                                <?= $reviewRating ?>/5
                            </small>

                        </div>

                    </div>


                    <?php if ($smartTag): ?>

                        <div class="smart-review-tag">
                            <?= htmlspecialchars($smartTag) ?>
                        </div>

                    <?php endif; ?>


                    <div class="review-comment">

                        <?= nl2br(htmlspecialchars($reviewComment)) ?>

                    </div>


                    <div class="review-card-footer">

                        <span>
                            ✓ Published instantly
                        </span>

                        <button
                            type="button"
                            class="helpful-btn"
                            onclick="markHelpful(this)"
                        >
                            👍 Helpful
                            <span>0</span>
                        </button>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <div class="empty-reviews">

            <div class="empty-review-icon">
                💬
            </div>

            <h3>
                Be the first to review this product
            </h3>

            <p>
                Your experience can help another customer make a confident
                choice.
            </p>

        </div>

    <?php endif; ?>

</section>


<style>

/* =========================================================
   SMART REVIEWS
========================================================= */

.smart-reviews {
    max-width: 1100px;
    margin: 50px auto;
    padding: 0 20px;
    font-family: Arial, sans-serif;
}


/* =========================================================
   HEADER
========================================================= */

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 25px;
    margin-bottom: 30px;
}

.review-eyebrow,
.mini-label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 2px;
    color: #a05270;
}

.review-header h2 {
    margin: 7px 0;
    font-size: 32px;
    color: #2f2430;
}

.review-header p {
    margin: 0;
    color: #777;
}

.review-live-badge {
    padding: 10px 16px;
    border-radius: 30px;
    background: #fff5f8;
    color: #a05270;
    font-size: 13px;
    font-weight: 600;
}

.live-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    margin-right: 7px;
    border-radius: 50%;
    background: #32a852;
}


/* =========================================================
   MESSAGES
========================================================= */

.review-message {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    padding: 16px 20px;
    border-radius: 14px;
    margin-bottom: 22px;
}

.success-message {
    background: #eefaf2;
    color: #216b39;
}

.error-message {
    background: #fff0f0;
    color: #a12b2b;
}

.review-message > span {
    font-size: 22px;
    font-weight: bold;
}

.review-message strong {
    display: block;
    margin-bottom: 3px;
}

.review-message p {
    margin: 0;
}


/* =========================================================
   RATING SUMMARY
========================================================= */

.rating-summary {
    display: grid;
    grid-template-columns: 230px 1fr;
    gap: 45px;
    padding: 30px;
    margin-bottom: 30px;
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 10px 35px rgba(80, 50, 65, 0.08);
}

.rating-main {
    text-align: center;
    padding: 10px;
}

.big-rating {
    font-size: 55px;
    font-weight: 700;
    color: #2f2430;
}

.big-stars,
.review-stars {
    color: #d89b2b;
    letter-spacing: 2px;
}

.big-stars {
    font-size: 25px;
}

.review-count {
    margin-top: 7px;
    color: #777;
    font-size: 14px;
}

.rating-bars {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 11px;
}

.rating-row {
    display: grid;
    grid-template-columns: 45px 1fr 35px;
    align-items: center;
    gap: 10px;
}

.rating-label,
.rating-number {
    font-size: 13px;
    color: #666;
}

.rating-track {
    height: 8px;
    overflow: hidden;
    border-radius: 10px;
    background: #eee;
}

.rating-fill {
    height: 100%;
    border-radius: 10px;
    background: #d89b2b;
}


/* =========================================================
   WRITE REVIEW
========================================================= */

.write-review-card {
    margin-bottom: 45px;
    padding: 30px;
    border-radius: 22px;
    background: linear-gradient(135deg, #fff7fa, #fff);
    border: 1px solid #f1dce4;
}

.write-review-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.write-review-heading h3 {
    margin: 6px 0;
    color: #30242c;
    font-size: 24px;
}

.write-review-heading p {
    margin: 0;
    color: #777;
}

.verified-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #dff5e6;
    color: #238346;
    font-size: 24px;
    font-weight: bold;
}


/* =========================================================
   STAR SELECTOR
========================================================= */

.star-rating-area {
    margin-bottom: 22px;
}

.star-rating-area > label,
.comment-area > label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700;
    color: #3c3037;
}

.star-selector {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    width: fit-content;
}

.star-selector input {
    display: none;
}

.star-selector label {
    cursor: pointer;
    font-size: 38px;
    padding: 0 3px;
    color: #d5d5d5;
    transition: 0.2s;
}

.star-selector label:hover,
.star-selector label:hover ~ label,
.star-selector input:checked ~ label {
    color: #d89b2b;
    transform: scale(1.05);
}

.rating-hint {
    margin-top: 5px;
    font-size: 12px;
    color: #888;
}


/* =========================================================
   SMART TAGS
========================================================= */

.smart-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    margin-bottom: 22px;
}

.smart-tags > span {
    width: 100%;
    margin-bottom: 3px;
    font-size: 13px;
    font-weight: 600;
    color: #555;
}

.tag-btn {
    border: 1px solid #ead7df;
    border-radius: 20px;
    padding: 8px 13px;
    background: #fff;
    color: #75505f;
    cursor: pointer;
    transition: 0.2s;
}

.tag-btn:hover {
    background: #a05270;
    color: #fff;
    border-color: #a05270;
}


/* =========================================================
   COMMENT
========================================================= */

.comment-area {
    position: relative;
}

.comment-area textarea {
    width: 100%;
    min-height: 150px;
    box-sizing: border-box;
    resize: vertical;
    padding: 15px;
    border: 1px solid #e4d8de;
    border-radius: 14px;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    line-height: 1.6;
    background: #fff;
}

.comment-area textarea:focus {
    border-color: #a05270;
    box-shadow: 0 0 0 3px rgba(160, 82, 112, 0.08);
}

.character-counter {
    text-align: right;
    margin-top: 5px;
    font-size: 12px;
    color: #888;
}


/* =========================================================
   FORM BOTTOM
========================================================= */

.review-form-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
}

.instant-note {
    color: #4b7c5a;
    font-size: 13px;
}

.submit-review-btn {
    border: none;
    border-radius: 12px;
    padding: 13px 25px;
    background: #a05270;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}

.submit-review-btn:hover {
    background: #873f5c;
    transform: translateY(-1px);
}


/* =========================================================
   DELETE
========================================================= */

.delete-review-form {
    margin-top: 12px;
    text-align: right;
}

.delete-review-btn {
    border: none;
    background: transparent;
    color: #b34141;
    cursor: pointer;
    font-size: 13px;
}

.delete-review-btn:hover {
    text-decoration: underline;
}


/* =========================================================
   PURCHASE MESSAGE
========================================================= */

.purchase-review-message {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 22px;
    margin-bottom: 40px;
    border-radius: 16px;
    background: #f8f5f7;
}

.lock-icon {
    font-size: 28px;
}

.purchase-review-message strong {
    color: #3a3036;
}

.purchase-review-message p {
    margin: 5px 0 0;
    color: #777;
}


/* =========================================================
   REVIEW LIST
========================================================= */

.review-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.review-list-header h3 {
    margin: 6px 0 0;
    font-size: 24px;
    color: #30242c;
}

.review-total-badge {
    padding: 8px 13px;
    border-radius: 20px;
    background: #f7edf1;
    color: #8b4d67;
    font-size: 13px;
    font-weight: 600;
}

.review-list {
    display: grid;
    gap: 17px;
}


/* =========================================================
   REVIEW CARD
========================================================= */

.review-card {
    padding: 24px;
    border-radius: 18px;
    background: #fff;
    border: 1px solid #eee4e8;
    transition: 0.2s;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(70, 40, 55, 0.07);
}

.review-card-top {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    border-radius: 50%;
    background: #f2dce5;
    color: #87475f;
    font-weight: 700;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.customer-info strong {
    color: #352a31;
}

.customer-info span {
    font-size: 12px;
    color: #3e8a57;
}

.customer-rating {
    margin-left: auto;
    text-align: right;
}

.customer-rating small {
    color: #888;
}

.smart-review-tag {
    display: inline-block;
    margin: 17px 0 10px;
    padding: 6px 10px;
    border-radius: 20px;
    background: #fff5d9;
    color: #8c651b;
    font-size: 12px;
    font-weight: 600;
}

.review-comment {
    margin-top: 17px;
    color: #4e454a;
    line-height: 1.7;
}

.review-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0eaed;
    font-size: 12px;
    color: #888;
}

.review-card-footer > span {
    color: #47845a;
}

.helpful-btn {
    border: none;
    background: transparent;
    color: #777;
    cursor: pointer;
}

.helpful-btn:hover {
    color: #a05270;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-reviews {
    text-align: center;
    padding: 55px 20px;
    border-radius: 20px;
    background: #faf7f9;
}

.empty-review-icon {
    font-size: 42px;
}

.empty-reviews h3 {
    margin: 15px 0 7px;
    color: #3a3036;
}

.empty-reviews p {
    margin: 0;
    color: #777;
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    .smart-reviews {
        margin-top: 30px;
    }

    .review-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .review-live-badge {
        align-self: flex-start;
    }

    .rating-summary {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .rating-main {
        border-bottom: 1px solid #eee;
        padding-bottom: 20px;
    }

    .review-form-bottom {
        flex-direction: column;
        align-items: stretch;
    }

    .submit-review-btn {
        width: 100%;
    }

    .customer-rating {
        margin-left: 0;
    }

    .review-card-top {
        flex-wrap: wrap;
    }

    .review-card-footer {
        gap: 10px;
        flex-direction: column;
        align-items: flex-start;
    }

}

</style>


<script>

/*
|--------------------------------------------------------------------------
| Character Counter
|--------------------------------------------------------------------------
*/

const reviewTextarea = document.getElementById('review-comment');
const reviewCharCount = document.getElementById('reviewCharCount');

function updateReviewCounter() {

    if (!reviewTextarea || !reviewCharCount) {
        return;
    }

    reviewCharCount.textContent =
        reviewTextarea.value.length;
}

if (reviewTextarea) {

    updateReviewCounter();

    reviewTextarea.addEventListener(
        'input',
        updateReviewCounter
    );
}


/*
|--------------------------------------------------------------------------
| Smart Review Tags
|--------------------------------------------------------------------------
*/

document.querySelectorAll('.tag-btn').forEach(function(button) {

    button.addEventListener('click', function() {

        if (!reviewTextarea) {
            return;
        }

        const tag = this.dataset.tag;

        const currentText =
            reviewTextarea.value.trim();

        if (currentText === '') {

            reviewTextarea.value =
                tag + ' — ';

        } else if (!currentText.includes(tag)) {

            reviewTextarea.value =
                currentText + ' ' + tag + ' — ';

        }

        updateReviewCounter();

        reviewTextarea.focus();

    });

});


/*
|--------------------------------------------------------------------------
| Helpful Button
|--------------------------------------------------------------------------
*/

function markHelpful(button) {

    const count =
        button.querySelector('span');

    let current =
        parseInt(count.textContent || '0', 10);

    current++;

    count.textContent = current;

    button.disabled = true;
    button.style.opacity = '0.6';
}

</script>