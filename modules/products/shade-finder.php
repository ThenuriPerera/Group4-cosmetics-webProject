<?php
/**
 * MODULE OWNER: Member 2 (Product Catalog & Smart Features)
 * Section 5.1 - Find Your Shade
 * Status: COMPLETE — clickable swatch palette (JS captures selection into hidden
 * input), saved into Beauty_Profile, guests are redirected to login (Section 3.1).
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$results = [];
$selectedTone = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedTone = $_POST['skin_tone'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM Product WHERE skin_tone = ?");
    $stmt->execute([$selectedTone]);
    $results = $stmt->fetchAll();

    $userId = current_user()['user_id'];
    $pdo->prepare(
        "INSERT INTO Beauty_Profile (user_id, skin_tone) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE skin_tone = VALUES(skin_tone)"
    )->execute([$userId, $selectedTone]);
}

require_once __DIR__ . '/../../includes/header.php';

$swatches = [
    'fair'   => '#f4dcc9',
    'light'  => '#e8bfa0',
    'medium' => '#c98f66',
    'tan'    => '#a56a3e',
    'deep'   => '#5c3a24',
];
?>
<section class="shade-finder">
    <h1>Find Your Shade</h1>
    <p>Tap the swatch closest to your skin tone.</p>

    <form method="post" id="shade-form">
        <input type="hidden" name="skin_tone" id="skin_tone_input" value="<?= htmlspecialchars($selectedTone ?? '') ?>">
        <div class="swatch-row">
            <?php foreach ($swatches as $tone => $hex): ?>
                <button type="button"
                        class="swatch <?= $selectedTone === $tone ? 'selected' : '' ?>"
                        data-tone="<?= $tone ?>"
                        style="background: <?= $hex ?>"
                        title="<?= ucfirst($tone) ?>">
                </button>
            <?php endforeach; ?>
        </div>
        <p id="tone-label"><?= $selectedTone ? 'Selected: ' . ucfirst($selectedTone) : '' ?></p>
        <button type="submit" id="find-btn" <?= $selectedTone ? '' : 'disabled' ?>>Find Matches</button>
    </form>

    <?php if ($results): ?>
        <h2>Products for you</h2>
        <div class="product-grid">
            <?php foreach ($results as $p): ?>
                <div class="product-card">
                    <h3><?= htmlspecialchars($p['product_name']) ?></h3>
                    <p>Rs. <?= number_format($p['price'], 2) ?></p>
                    <a href="/modules/products/product.php?id=<?= $p['product_id'] ?>">View</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($selectedTone): ?>
        <p>No products tagged for this tone yet — an editor needs to add some.</p>
    <?php endif; ?>
</section>

<script>
document.querySelectorAll('.swatch').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.swatch').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        const tone = btn.dataset.tone;
        document.getElementById('skin_tone_input').value = tone;
        document.getElementById('tone-label').textContent = 'Selected: ' + tone.charAt(0).toUpperCase() + tone.slice(1);
        document.getElementById('find-btn').disabled = false;
    });
});
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
