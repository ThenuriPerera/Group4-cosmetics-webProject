<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * Status: COMPLETE — profile view/edit + full address CRUD.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$userId = current_user()['user_id'];
$message = '';

// Update profile info
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $pdo->prepare("UPDATE User SET name = ?, phone = ? WHERE user_id = ?")
        ->execute([$name, $phone, $userId]);
    $_SESSION['user']['name'] = $name;
    $message = 'Profile updated.';
}

// Add address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $pdo->prepare(
        "INSERT INTO Address (user_id, street, city, postal_code, state, country) VALUES (?, ?, ?, ?, ?, ?)"
    )->execute([
        $userId,
        trim($_POST['street']),
        trim($_POST['city']),
        trim($_POST['postal_code']),
        trim($_POST['state']),
        trim($_POST['country']),
    ]);
    $message = 'Address added.';
}

// Delete address
if (isset($_GET['delete_address'])) {
    $pdo->prepare("DELETE FROM Address WHERE address_id = ? AND user_id = ?")
        ->execute([$_GET['delete_address'], $userId]);
    header('Location: ' . lg_url('/modules/auth/profile.php'));
    exit;
}

$user = $pdo->prepare("SELECT * FROM User WHERE user_id = ?");
$user->execute([$userId]);
$user = $user->fetch();

$addresses = $pdo->prepare("SELECT * FROM Address WHERE user_id = ?");
$addresses->execute([$userId]);
$addresses = $addresses->fetchAll();

// Presentation is kept in views/auth/profile.view.php.
$pageKey = 'auth/profile';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/auth/profile.view.php';
require_once __DIR__ . '/../../includes/footer.php';
