<?php
/**
 * Lumine Glow - Admin User Seeder
 *
 * Run this ONCE after importing database/schema.sql to create the admin
 * account with a properly hashed password.
 *
 * Usage (from the project root):
 *   php tools/seed-admin.php
 *
 * Or open in browser:
 *   http://localhost/Group4-cosmetics-webProject/tools/seed-admin.php
 *
 * Default admin credentials:
 *   Email:    admin@lumineglow.com
 *   Password: 123456789
 */

require_once __DIR__ . '/../config/db.php';

$adminEmail    = 'admin@lumineglow.com';
$adminPassword = '123456789';
$adminName     = 'Admin User';
$adminPhone    = '0770000000';

// Check if admin already exists
$stmt = $pdo->prepare("SELECT user_id FROM User WHERE email = ?");
$stmt->execute([$adminEmail]);

if ($stmt->fetch()) {
    // Admin exists — update the password to ensure it is hashed correctly
    $hashed = password_hash($adminPassword, PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE User SET password = ?, role = 'admin', status = 'active' WHERE email = ?");
    $update->execute([$hashed, $adminEmail]);
    echo "Admin password reset to '123456789' (hashed).\n";
} else {
    // Create the admin user
    $hashed = password_hash($adminPassword, PASSWORD_DEFAULT);
    $insert = $pdo->prepare(
        "INSERT INTO User (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, 'admin', 'active')"
    );
    $insert->execute([$adminName, $adminEmail, $adminPhone, $hashed]);
    echo "Admin user created.\n";
}

echo "  Email:    $adminEmail\n";
echo "  Password: $adminPassword\n";
echo "\nYou can now log in at the admin login page.\n";
echo "IMPORTANT: Delete or restrict access to this file after use.\n";