<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="auth-form">
    <div class="auth-header"><div class="auth-mark" aria-hidden="true">L</div><p class="eyebrow">LUMINÉ GLOW</p><h1>Welcome back</h1><p>Sign in to your beauty space.</p></div>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Email <input type="email" autocomplete="email" name="email" required></label>
        <label>Password <input type="password" autocomplete="current-password" name="password" required></label>
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/auth/register.php">Register</a></p>
</section>
