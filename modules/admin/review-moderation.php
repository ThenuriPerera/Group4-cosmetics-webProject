<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $newStatus = $_POST['decision'] === 'approve' ? 'Approved' : 'Rejected';
    $pdo->prepare("UPDATE Review SET status = ? WHERE review_id = ?")
        ->execute([$newStatus, $_POST['review_id']]);
    header('Location: ' . lg_url('/modules/admin/review-moderation.php'));
    exit;
}

$stmt = $pdo->query(
    "SELECT r.*, u.name AS user_name, p.product_name FROM Review r
     JOIN User u ON r.user_id = u.user_id
     JOIN Product p ON r.product_id = p.product_id
     WHERE r.status = 'Pending' ORDER BY r.rating_date ASC"
);
$pendingReviews = $stmt->fetchAll();

// Presentation is kept in views/admin/review-moderation.view.php.
$pageKey = 'admin/review-moderation';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/admin/review-moderation.view.php';
require_once __DIR__ . '/../../includes/footer.php';
