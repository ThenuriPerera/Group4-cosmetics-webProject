<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="hero">
<div class="hero-overlay"></div>
<div class="hero-content">
<p class="eyebrow">BEAUTY, SIMPLIFIED</p>
<h1>Glow in your<br><em>own shade.</em></h1>
<p>Discover skincare and makeup selected to help you feel confident in your natural beauty.</p>
<div class="hero-buttons">
<a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>" class="btn">Shop now</a>
<a href="<?= htmlspecialchars(lg_url('modules/products/beauty-quiz.php')) ?>" class="btn btn-outline">Find my routine</a>
</div></div>
</section>
<section class="section">
<div class="section-heading"><div><p class="eyebrow">EXPLORE</p><h2>Shop by category</h2></div><a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">View all &rarr;</a></div>
<div class="category-grid">
<?php foreach ($homeCategories as $i => $category): ?>
<a class="category-card" href="<?= htmlspecialchars(lg_url('modules/products/index.php?category_id=' . (int)$category['category_id'])) ?>">
<span><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
<h3><?= htmlspecialchars($category['category_name']) ?></h3>
<p><?= htmlspecialchars($category['category_description'] ?? '') ?></p>
</a>
<?php endforeach; ?>
<?php if (!$homeCategories): ?><p>Our collection is coming soon.</p><?php endif; ?>
</div>
</section>
<section class="section soft">
<div class="section-heading"><div><p class="eyebrow">THE COLLECTION</p><h2>Discover your next favourite</h2></div><a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Shop all &rarr;</a></div>
<div class="product-grid">
<?php foreach ($homeProducts as $product): ?>
<article class="product-card">
<?php $photo = lg_image_url($product); ?>
<div class="product-image">
<?php if ($photo): ?><img src="<?= htmlspecialchars($photo, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['product_name']) ?>" loading="lazy">
<?php else: ?><div class="product-placeholder">Luminé Glow<br>Photo coming soon</div><?php endif; ?>
</div>
<div class="product-info"><h3><?= htmlspecialchars($product['product_name']) ?></h3><p class="price">Rs. <?= number_format($product['price'], 2) ?></p><a class="text-link" href="<?= htmlspecialchars(lg_url('modules/products/product.php?id=' . (int)$product['product_id'])) ?>">View product &rarr;</a></div>
</article>
<?php endforeach; ?>
<?php if (!$homeProducts): ?><p>New beauty essentials are on their way.</p><?php endif; ?>
</div>
</section>
<section class="quiz-banner"><div><p class="eyebrow">NOT SURE WHAT YOU NEED?</p><h2>Let us help you build your beauty routine.</h2></div><a class="btn" href="<?= htmlspecialchars(lg_url('modules/products/beauty-quiz.php')) ?>">Take the beauty quiz</a></section>
