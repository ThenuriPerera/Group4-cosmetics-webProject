
<?php
/**
 * Lumine Glow - Session & Role-Based Access Control helpers
 * Shared by all 4 modules. Owner: Member 1 (Auth), but everyone uses this.
 */

require_once __DIR__ . '/../config/paths.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Current User
|--------------------------------------------------------------------------
*/

function current_user() {
    return $_SESSION['user'] ?? null;
}


/*
|--------------------------------------------------------------------------
| Login Check
|--------------------------------------------------------------------------
*/

function is_logged_in() {
    return isset($_SESSION['user']);
}


/*
|--------------------------------------------------------------------------
| Current Role
|--------------------------------------------------------------------------
*/

function current_role() {
    return $_SESSION['user']['role'] ?? 'guest';
}


/*
|--------------------------------------------------------------------------
| Role-Based Access Control
|--------------------------------------------------------------------------
|
| Example:
| require_role(['customer']);
| require_role(['editor', 'admin']);
|
*/

function require_role(array $allowedRoles) {

    $role = current_role();

    if (!in_array($role, $allowedRoles, true)) {

        header(
            'Location: ' .
            lg_url('/modules/auth/login.php?redirect=') .
            urlencode($_SERVER['REQUEST_URI'])
        );

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Require Login
|--------------------------------------------------------------------------
*/

function require_login() {

    if (!is_logged_in()) {

        header(
            'Location: ' .
            lg_url('/modules/auth/login.php?redirect=') .
            urlencode($_SERVER['REQUEST_URI'])
        );

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| CSRF Token
|--------------------------------------------------------------------------
|
| Creates one CSRF token for the current session.
|
*/

function csrf_token() {

    if (empty($_SESSION['csrf_token'])) {

        $_SESSION['csrf_token'] =
            bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}


/*
|--------------------------------------------------------------------------
| Require Valid CSRF Token
|--------------------------------------------------------------------------
|
| Used for POST requests.
|
*/

function require_csrf_token() {

    $token = $_POST['csrf_token'] ?? '';

    if (
        empty($token) ||
        empty($_SESSION['csrf_token']) ||
        !hash_equals(
            $_SESSION['csrf_token'],
            $token
        )
    ) {

        http_response_code(403);

        exit('Invalid CSRF token.');
    }
}
