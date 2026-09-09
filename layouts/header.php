<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> | Luminé Glow</title>
<link rel="stylesheet" href="<?= htmlspecialchars(lg_url('assets/css/base.css')) ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(lg_url('assets/css/layout.css')) ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(lg_url('assets/css/components.css')) ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(lg_url('assets/css/pages/' . $pageStyle . '.css')) ?>">
<script src="<?= htmlspecialchars(lg_url('assets/js/main.js')) ?>" defer></script>
<?php if ($pageScript): ?><script src="<?= htmlspecialchars(lg_url('assets/js/pages/' . $pageScript . '.js')) ?>" defer></script><?php endif; ?>
</head>
<body class="page-<?= htmlspecialchars($pageStyle) ?>">
<a class="skip-link" href="#main-content">Skip to content</a>
<header class="site-header">
<a class="logo" href="<?= htmlspecialchars(lg_url('index.php')) ?>">Luminé <span>Glow</span></a>
<button class="menu-toggle" type="button" aria-controls="main-nav" aria-expanded="false">Menu</button>
<nav id="main-nav" aria-label="Main navigation">
<a href="<?= htmlspecialchars(lg_url('index.php')) ?>">Home</a>
<a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">Shop</a>
<a href="<?= htmlspecialchars(lg_url('modules/products/shade-finder.php')) ?>">Find your shade</a>
<a href="<?= htmlspecialchars(lg_url('modules/products/beauty-quiz.php')) ?>">Beauty quiz</a>
<?php if (is_logged_in()): ?>
<a href="<?= htmlspecialchars(lg_url('modules/auth/profile.php')) ?>">My account</a>
<?php if (in_array(current_role(), ['admin','editor'], true)): ?><a href="<?= htmlspecialchars(lg_url(current_role() === 'admin' ? 'modules/admin/dashboard.php' : 'modules/products/manage.php')) ?>">Manage store</a><?php endif; ?>
<a href="<?= htmlspecialchars(lg_url('modules/auth/logout.php')) ?>">Logout</a>
<?php else: ?><a href="<?= htmlspecialchars(lg_url('modules/auth/login.php')) ?>">Login</a><?php endif; ?>
<a class="bag" href="<?= htmlspecialchars(lg_url('modules/cart/cart.php')) ?>">Bag</a>
</nav>
</header>
<main id="main-content" class="site-main">
<?php if ($pageStyle !== 'home' && $pageStyle !== 'auth'): ?>
<div class="breadcrumbs"><a href="<?= htmlspecialchars(lg_url('index.php')) ?>">Home</a><span aria-hidden="true">/</span><span><?= htmlspecialchars($pageTitle) ?></span></div>
<div class="page-heading"><p class="eyebrow"><?= $pageStyle === 'admin' ? 'LUMINÉ GLOW · STORE MANAGEMENT' : 'BEAUTY, SIMPLIFIED' ?></p><h1><?= htmlspecialchars($pageTitle) ?></h1><p><?= htmlspecialchars($pageDescription) ?></p></div>
<?php endif; ?>
<?php if ($pageStyle === 'account'): ?>
<nav class="section-nav" aria-label="Your account">
<a href="<?= htmlspecialchars(lg_url('modules/auth/profile.php')) ?>">Profile & addresses</a>
<a href="<?= htmlspecialchars(lg_url('modules/orders/track-order.php')) ?>">Orders</a>
<a href="<?= htmlspecialchars(lg_url('modules/orders/wishlist.php')) ?>">Wishlist</a>
</nav>
<?php endif; ?>
<?php if ($pageStyle === 'shopping'): ?>
<ol class="checkout-steps" aria-label="Checkout progress">
<?php foreach (['cart/cart' => 'Bag', 'cart/checkout' => 'Delivery', 'cart/payment' => 'Payment'] as $stepKey => $stepLabel): ?>
<li <?= ($pageKey === $stepKey) ? 'aria-current="step"' : '' ?>><span><?= htmlspecialchars($stepLabel) ?></span></li>
<?php endforeach; ?></ol>
<?php endif; ?>
<?php if ($pageStyle === 'admin'): ?>
<div class="admin-shell"><aside class="admin-sidebar"><p class="eyebrow">WORKSPACE</p>
<?php if (current_role() === 'admin'): ?><a href="<?= htmlspecialchars(lg_url('modules/admin/dashboard.php')) ?>">Overview</a><?php endif; ?>
<a href="<?= htmlspecialchars(lg_url('modules/products/manage.php')) ?>">Products</a>
<?php if (current_role() === 'admin'): ?><a href="<?= htmlspecialchars(lg_url('modules/admin/review-moderation.php')) ?>">Reviews</a><?php endif; ?>
<a href="<?= htmlspecialchars(lg_url('modules/products/index.php')) ?>">View storefront</a>
</aside><div class="admin-content">
<?php endif; ?>
