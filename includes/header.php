<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminé Glow</title>
    <link rel="stylesheet" href="<?= app_url('/assets/css/style.css') ?>">
</head>
<body>
<header class="site-header">
    <div class="logo">Luminé Glow</div>
    <nav>
        <a href="<?= app_url('/index.php') ?>">Home</a>
        <a href="<?= app_url('/modules/products/index.php') ?>">Shop</a>
        <a href="<?= app_url('/modules/products/shade-finder.php') ?>">Find Your Shade</a>
        <a href="<?= app_url('/modules/products/beauty-quiz.php') ?>">Beauty Quiz</a>
        <a href="<?= app_url('/modules/cart/cart.php') ?>">Cart</a>
        <?php if (is_logged_in()): ?>
            <a href="<?= app_url('/modules/orders/track-order.php') ?>">My Orders</a>
            <a href="<?= app_url('/modules/auth/logout.php') ?>">Logout (<?= htmlspecialchars(current_user()['name']) ?>)</a>
        <?php else: ?>
            <a href="<?= app_url('/modules/auth/login.php') ?>">Login</a>
            <a href="<?= app_url('/modules/auth/register.php') ?>">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="site-main">
