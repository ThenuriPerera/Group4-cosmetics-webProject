<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * Status: COMPLETE — role-based redirect after login added.
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM User WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $user['status'] === 'suspended') {
        $error = 'This account has been suspended. Contact support.';
        } elseif ($user && password_verify($password, $user['password'])) {
        
        // SECURITY FIX 1: Prevent Session Fixation
        // Assigns a brand new session ID so attackers can't hijack a pre-set ID
        session_regenerate_id(true);

        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ];

        // SECURITY FIX 2: Prevent Open Redirects
        // Only redirect if the path is internal (starts with '/')
        if (!empty($_GET['redirect']) && strpos($_GET['redirect'], '/') === 0) {
            header('Location: ' . $_GET['redirect']);
            exit;
        }

        // Otherwise route by role
        switch ($user['role']) {
            case 'admin':
                header('Location: ' . lg_url('/modules/admin/dashboard.php'));
                break;
            case 'editor':
                header('Location: ' . lg_url('/modules/products/manage.php'));
                break;
            default:
                header('Location: ' . lg_url('/index.php'));
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

// Presentation is kept in views/auth/login.view.php.
$pageKey = 'auth/login';
require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/auth/login.view.php';
require_once __DIR__ . '/../../includes/footer.php';
