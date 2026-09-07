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
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ];

        // Explicit redirect param wins if it was set (e.g. require_login() bounced them here)
        if (!empty($_GET['redirect'])) {
            header('Location: ' . $_GET['redirect']);
            exit;
        }

        // Otherwise route by role
        switch ($user['role']) {
            case 'admin':
                header('Location: /modules/admin/dashboard.php');
                break;
            case 'editor':
                header('Location: /modules/products/manage.php');
                break;
            default:
                header('Location: /index.php');
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="auth-form">
    <h1>Login</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Email <input type="email" name="email" required></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="/modules/auth/register.php">Register</a></p>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
