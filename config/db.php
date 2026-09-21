<?php
/**
 * Lumine Glow - Database Connection
 * Shared by all modules.
 */

require_once __DIR__ . '/env.php';

$DB_HOST = lume_env('DB_HOST', 'localhost');
$DB_NAME = lume_env('DB_NAME', 'lumine_glow');
$DB_USER = lume_env('DB_USER', 'root');
$DB_PASS = lume_env('DB_PASS', '');

// Load local overrides (this file is gitignored)
$localConfig = __DIR__ . '/db.local.php';
if (file_exists($localConfig)) {
    require_once $localConfig;
}

try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}