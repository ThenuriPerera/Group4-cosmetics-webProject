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

$swatches = [
    'fair'   => '#f4dcc9',
    'light'  => '#e8bfa0',
    'medium' => '#c98f66',
    'tan'    => '#a56a3e',
    'deep'   => '#5c3a24',
];

// Presentation is kept in views/products/shade-finder.view.php.
$pageKey = 'products/shade-finder';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/shade-finder.view.php';
require_once __DIR__ . '/../../includes/footer.php';
