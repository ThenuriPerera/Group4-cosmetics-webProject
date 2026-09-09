<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="admin-reviews">

    <?php foreach ($pendingReviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['user_name']) ?></strong> on
            <strong><?= htmlspecialchars($r['product_name']) ?></strong> — <?= $r['rating'] ?>/5
            <p><?= htmlspecialchars($r['comment']) ?></p>
            <form method="post" class="inline-form">
                <input type="hidden" name="review_id" value="<?= $r['review_id'] ?>">
                <button type="submit" name="decision" value="approve">Approve</button>
                <button type="submit" name="decision" value="reject">Reject</button>
            </form>
        </div>
    <?php endforeach; ?>
    <?php if (empty($pendingReviews)): ?><div class="empty-state"><h2>You're all caught up.</h2><p>New customer reviews will appear here for moderation.</p></div><?php endif; ?>
</section>
