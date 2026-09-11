<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * Handles new user registration -> User table.
 *
 * Registration includes:
 * - Required-field validation
 * - Email format validation
 * - Password strength validation
 * - Duplicate email checking
 * - Secure password hashing
 * - First-time customer onboarding redirect
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$error = '';
$old = ['name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $old = ['name' => $name, 'email' => $email, 'phone' => $phone];

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one letter and one number.';
    } else {
        $check = $pdo->prepare("SELECT user_id FROM User WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'An account with this email already exists. Try logging in instead.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO User (name, email, phone, password, role) VALUES (?, ?, ?, ?, 'customer')"
            );
            $stmt->execute([$name, $email, $phone, $hashed]);

            $_SESSION['user'] = [
                'user_id' => $pdo->lastInsertId(),
                'name'    => $name,
                'role'    => 'customer',
            ];

            // First-time customers go straight into onboarding (Section 3.2)
            header('Location: ' . lg_url('/modules/products/beauty-quiz.php?onboarding=1'));
            exit;
        }
    }
}

// Presentation is kept in views/auth/register.view.php.
$pageKey = 'auth/register';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/auth/register.view.php';
require_once __DIR__ . '/../../includes/footer.php';
