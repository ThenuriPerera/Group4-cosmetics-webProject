<?php

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_login();

$selectedTone = '';
$results = [];

$shadePalette = [
    'tone-1' => '#f8e5d4',
    'tone-2' => '#f0ceb5',
    'tone-3' => '#dfae88',
    'tone-4' => '#c98e68',
    'tone-5' => '#ae704d',
    'tone-6' => '#8d5539',
    'tone-7' => '#673b29',
    'tone-8' => '#3f241b'
];

/*
 * The database currently uses five skin_tone values.
 * The eight visual tones are mapped to the closest database tone.
 */
$toneDatabaseMap = [
    'tone-1' => 'fair',
    'tone-2' => 'fair',
    'tone-3' => 'light',
    'tone-4' => 'medium',
    'tone-5' => 'medium',
    'tone-6' => 'tan',
    'tone-7' => 'deep',
    'tone-8' => 'deep'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $selectedTone = trim(
        $_POST['skin_tone'] ?? ''
    );

    if (isset($toneDatabaseMap[$selectedTone])) {

        $databaseTone = $toneDatabaseMap[$selectedTone];

        $stmt = $pdo->prepare("
            SELECT
                p.*,
                b.brand_name,
                c.category_name
            FROM Product p
            LEFT JOIN Brand b
                ON b.brand_id = p.brand_id
            LEFT JOIN Category c
                ON c.category_id = p.category_id
            WHERE LOWER(p.skin_tone) = ?
            AND (
                LOWER(p.product_type) IN (
                    'foundation',
                    'concealer',
                    'blush',
                    'bronzer',
                    'highlighter',
                    'powder'
                )
                OR LOWER(p.product_name) LIKE '%foundation%'
                OR LOWER(p.product_name) LIKE '%concealer%'
                OR LOWER(p.product_name) LIKE '%blush%'
                OR LOWER(p.product_name) LIKE '%bronzer%'
                OR LOWER(p.product_name) LIKE '%highlighter%'
                OR LOWER(p.product_name) LIKE '%powder%'
            )
            ORDER BY p.product_name ASC
        ");

        $stmt->execute([$databaseTone]);
        $results = $stmt->fetchAll();

        $userId = (int) current_user()['user_id'];

        $profile = $pdo->prepare("
            INSERT INTO Beauty_Profile
                (user_id, skin_tone)
            VALUES
                (?, ?)
            ON DUPLICATE KEY UPDATE
                skin_tone = VALUES(skin_tone)
        ");

        $profile->execute([
            $userId,
            $databaseTone
        ]);
    }
}

$pageKey = 'products/shade-finder';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/products/shade-finder.view.php';
require_once __DIR__ . '/../../includes/footer.php';