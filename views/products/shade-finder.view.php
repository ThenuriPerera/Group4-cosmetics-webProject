<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="shade-finder">

    <p>Tap the swatch closest to your skin tone.</p>

    <form method="post" id="shade-form">
        <input type="hidden" name="skin_tone" id="skin_tone_input" value="<?= htmlspecialchars($selectedTone ?? '') ?>">
        <div class="swatch-row">
            <?php foreach ($swatches as $tone => $hex): ?>
                <button type="button"
                        class="swatch <?= $selectedTone === $tone ? 'selected' : '' ?>"
                        data-tone="<?= $tone ?>"
                        style="background: <?= $hex ?>"
                        title="<?= ucfirst($tone) ?>" aria-label="<?= ucfirst($tone) ?> skin tone" aria-pressed="<?= $selectedTone === $tone ? 'true' : 'false' ?>">
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
                    <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/products/product.php?id=<?= $p['product_id'] ?>">View</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif ($selectedTone): ?>
        <div class="empty-state"><h2>More shades are on their way.</h2><p>No products match this tone yet. Explore the collection or try another shade.</p><a class="btn btn-outline" href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Shop the collection</a></div>
    <?php endif; ?>
</section>
