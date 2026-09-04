<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $newStatus = $_POST['decision'] === 'approve' ? 'Approved' : 'Rejected';
    $pdo->prepare("UPDATE Review SET status = ? WHERE review_id = ?")
        ->execute([$newStatus, $_POST['review_id']]);
    header('Location: /modules/admin/review-moderation.php');
    exit;
}

$stmt = $pdo->query(
    "SELECT r.*, u.name AS user_name, p.product_name FROM Review r
     JOIN User u ON r.user_id = u.user_id
     JOIN Product p ON r.product_id = p.product_id
     WHERE r.status = 'Pending' ORDER BY r.rating_date ASC"
);
$pendingReviews = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="admin-reviews">
    <h1>Review Moderation</h1>
    <?php foreach ($pendingReviews as $r): ?>
        <div class="review">
            <strong><?= htmlspecialchars($r['user_name']) ?></strong> on
            <strong><?= htmlspecialchars($r['product_name']) ?></strong> — <?= $r['rating'] ?>/5
            <p><?= htmlspecialchars($r['comment']) ?></p>
            <form method="post" style="display:inline">
                <input type="hidden" name="review_id" value="<?= $r['review_id'] ?>">
                <button type="submit" name="decision" value="approve">Approve</button>
                <button type="submit" name="decision" value="reject">Reject</button>
            </form>
        </div>
    <?php endforeach; ?>
    <?php if (empty($pendingReviews)): ?><p>No pending reviews.</p><?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?> 