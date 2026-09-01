<?php
/**
 * MODULE OWNER: Member 4 (Order Tracking, Reviews, Wishlist, Admin Analytics)
 * TODO (Member 4):
 *  - "Move to cart in one click" button (Section 3.2)
 *  - Prevent duplicate wishlist entries
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_wishlist'])) {
    $pdo->prepare("INSERT INTO Wishlist (user_id, product_id) VALUES (?, ?)")
        ->execute([$userId, $_POST['product_id']]);
    header('Location: /modules/orders/wishlist.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT w.wishlist_id, p.* FROM Wishlist w JOIN Product p ON w.product_id = p.product_id WHERE w.user_id = ?"
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="wishlist-page">
    <h1>My Wishlist</h1>
    <div class="product-grid">
        <?php foreach ($items as $p): ?>
            <div class="product-card">
                <h3><?= htmlspecialchars($p['product_name']) ?></h3>
                <p>Rs. <?= number_format($p['price'], 2) ?></p>
                <!-- TODO: move-to-cart button -->
            </div>
        <?php endforeach; ?>
        <?php if (empty($items)): ?><p>Your wishlist is empty.</p><?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
