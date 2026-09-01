<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/header.php';
?>
<section class="hero">
    <h1>Luminé Glow — Smart Beauty Experience</h1>
    <p>Find products matched to your skin tone and skin type.</p>
    <a class="btn" href="/modules/products/index.php">Shop Now</a>
    <a class="btn" href="/modules/products/shade-finder.php">Find Your Shade</a>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
