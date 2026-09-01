<?php
/**
 * MODULE OWNER: Member 1 (Auth & User Management)
 * Handles new user registration -> User table.
 * TODO (Member 1):
 *  - Add server-side validation (email format, password strength)
 *  - Hash password with password_hash()
 *  - Redirect first-time users into onboarding (skin quiz / shade finder)
 */
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all required fields.';
    } else {
        // TODO: check for duplicate email before insert
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

        // TODO: redirect to onboarding (Beauty Quiz) per Section 3.2 of proposal
        header('Location: /modules/products/beauty-quiz.php?onboarding=1');
        exit;
    }
}

require_once __DIR__ . '/../../includes/header.php';
?>
<section class="auth-form">
    <h1>Create Account</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Name <input type="text" name="name" required></label>
        <label>Email <input type="email" name="email" required></label>
        <label>Phone <input type="tel" name="phone"></label>
        <label>Password <input type="password" name="password" required></label>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="/modules/auth/login.php">Login</a></p>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
