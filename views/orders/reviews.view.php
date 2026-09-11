<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="reviews">

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
            <label>Your review<textarea name="comment" placeholder="Tell us about your experience..." required></textarea></label>
            <button type="submit">Submit Review</button>
            <p><small>Your review will appear after admin approval.</small></p>
        </form>
    <?php else: ?>
        <p>Only customers who have purchased this product can leave a review.</p>
    <?php endif; ?>
</section>
