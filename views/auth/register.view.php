<?php if (!defined('LG_VIEW')) { http_response_code(404); exit; } ?>
<section class="auth-form">
    <div class="auth-header"><div class="auth-mark" aria-hidden="true">L</div><p class="eyebrow">LUMINÉ GLOW</p><h1>Create your account</h1><p>Your own space for everyday beauty.</p></div>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post">
        <label>Name <input type="text" autocomplete="name" name="name" value="<?= htmlspecialchars($old['name']) ?>" required></label>
        <label>Email <input type="email" autocomplete="email" name="email" value="<?= htmlspecialchars($old['email']) ?>" required></label>
        <label>Phone <input type="tel" autocomplete="tel" name="phone" value="<?= htmlspecialchars($old['phone']) ?>"></label>
        <label>Password <input type="password" autocomplete="new-password" name="password" required minlength="8"></label>
        <small>At least 8 characters, with a letter and a number.</small>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="<?= htmlspecialchars(LG_BASE_PATH, ENT_QUOTES, 'UTF-8') ?>/modules/auth/login.php">Login</a></p>
</section>
