<?php
/**
 * Lumine Glow - Session & Role-Based Access Control helpers
 * Shared by all 4 modules. Owner: Member 1 (Auth), but everyone uses this.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user() {
    return $_SESSION['user'] ?? null; // ['user_id'=>, 'name'=>, 'role'=>]
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function current_role() {
    return $_SESSION['user']['role'] ?? 'guest';
}

/**
 * Call at the top of any page that needs to restrict access.
 * Example: require_role(['customer']);
 *          require_role(['editor','admin']);
 */
function require_role(array $allowedRoles) {
    $role = current_role();
    if (!in_array($role, $allowedRoles, true)) {
        header('Location: /modules/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /modules/auth/login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}
