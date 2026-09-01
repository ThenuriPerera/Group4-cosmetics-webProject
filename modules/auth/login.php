<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * TODO (Member 1):
 *  - Add rate limiting / lockout after failed attempts
 *  - Add "remember me" option
 *  - Redirect Editor -> editor panel, Admin -> admin dashboard after login
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

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ];
        $redirect = $_GET['redirect'] ?? '/index.php';
        header('Location: ' . $redirect);
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
