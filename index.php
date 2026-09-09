<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
$pageKey = 'home';
// Read existing records for the reference design's category and product sections.
$homeCategories = $pdo->query("SELECT * FROM Category ORDER BY category_id")->fetchAll();
$homeProducts = $pdo->query("SELECT * FROM Product ORDER BY product_id DESC LIMIT 4")->fetchAll();
require_once __DIR__ . '/includes/header.php';
require __DIR__ . '/views/home.view.php';
require_once __DIR__ . '/includes/footer.php';
