<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminé Glow</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="logo">Luminé Glow</div>
    <nav>
        <a href="/index.php">Home</a>
        <a href="/modules/products/index.php">Shop</a>
        <a href="/modules/products/shade-finder.php">Find Your Shade</a>
        <a href="/modules/products/beauty-quiz.php">Beauty Quiz</a>
        <a href="/modules/cart/cart.php">Cart</a>
        <?php if (is_logged_in()): ?>
            <a href="/modules/orders/track-order.php">My Orders</a>
            <a href="/modules/auth/logout.php">Logout (<?= htmlspecialchars(current_user()['name']) ?>)</a>
        <?php else: ?>
            <a href="/modules/auth/login.php">Login</a>
            <a href="/modules/auth/register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="site-main">
