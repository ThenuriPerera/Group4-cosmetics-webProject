<?php

require_once __DIR__ . '/../config/paths.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in()
{
    return isset($_SESSION['user']);
}

function current_role()
{
    return $_SESSION['user']['role'] ?? 'guest';
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token)
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function require_csrf_token()
{
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        http_response_code(419);
        exit('This form has expired. Please refresh the page and try again.');
    }
}

function require_login()
{
    if (!is_logged_in()) {
        header(
            'Location: ' .
            lg_url('/modules/auth/login.php?redirect=') .
            urlencode($_SERVER['REQUEST_URI'])
        );
        exit;
    }
}

function require_role(array $allowedRoles)
{
    $role = current_role();

    if (!in_array($role, $allowedRoles, true)) {
        if (is_logged_in()) {
            http_response_code(403);
            exit('You do not have permission to access this page.');
        }

        header(
            'Location: ' .
            lg_url('/modules/auth/login.php?redirect=') .
            urlencode($_SERVER['REQUEST_URI'])
        );
        exit;
    }
}