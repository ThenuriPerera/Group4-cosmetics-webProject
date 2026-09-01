<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Section 5.1 - Find Your Shade
 * TODO (Member 2):
 *  - Build a real visual shade palette (swatches) in JS
 *  - Save selection into Beauty_Profile.skin_tone for logged-in users
 *  - Guests: prompt to register per Section 3.1
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login(); // guests cannot use this feature (Section 3.1)

    $skinTone = $_POST['skin_tone'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM Product WHERE skin_tone = ?");
    $stmt->execute([$skinTone]);
    $results = $stmt->fetchAll();

    // Save to Beauty_Profile
    $userId = current_user()['user_id'];
    $pdo->prepare(
        "INSERT INTO Beauty_Profile (user_id, skin_tone) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE skin_tone = VALUES(skin_tone)"
    )->execute([$userId, $skinTone]);
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="shade-finder">
    <h1>Find Your Shade</h1>
    <form method="post">
        <!-- TODO: replace with clickable colour swatches (JS captures value into hidden input) -->
        <label>Skin tone
            <select name="skin_tone" required>
                <option value="fair">Fair</option>
                <option value="light">Light</option>
                <option value="medium">Medium</option>
                <option value="tan">Tan</option>
                <option value="deep">Deep</option>
            </select>
        </label>
        <button type="submit">Find Matches</button>
    </form>

    <?php if ($results): ?>
        <div class="product-grid">
            <?php foreach ($results as $p): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($p['product_name']) ?></h3>
                    <p>Rs. <?= number_format($p['price'], 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
