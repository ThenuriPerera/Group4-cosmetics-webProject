<?php
/**
 * Project URL helpers.
 * Supports localhost subfolders and virtual-host document roots.
 */

if (!defined('LG_BASE_PATH')) {
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));

    $scriptFile = str_replace(
        '\\',
        '/',
        $_SERVER['SCRIPT_FILENAME'] ?? ''
    );

    $scriptName = rawurldecode(
        str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '')
    );

    $base = '';

    if (stripos($scriptFile, $projectRoot . '/') === 0) {
        $relative = substr($scriptFile, strlen($projectRoot));

        if (
            $relative !== '' &&
            substr($scriptName, -strlen($relative)) === $relative
        ) {
            $base = substr($scriptName, 0, -strlen($relative));
        }
    }

    // Support project folder names containing spaces.
    $base = implode(
        '/',
        array_map('rawurlencode', explode('/', rtrim($base, '/')))
    );

    define('LG_BASE_PATH', $base);
}

/**
 * Create a URL relative to the project root.
 */
function lg_url($path = '')
{
    return LG_BASE_PATH . '/' . ltrim((string) $path, '/');
}

/**
 * Resolve product image paths consistently across pages.
 */
function lg_image_url(array $product)
{
    $value = trim((string) ($product['image'] ?? ''));

    if ($value === '') {
        $value = trim((string) ($product['image_url'] ?? ''));
    }

    if ($value === '') {
        return '';
    }

    // Allow externally hosted HTTP/HTTPS images.
    if (preg_match('~^https?://~i', $value)) {
        return $value;
    }

    // Reject unsupported URL schemes.
    if (
        preg_match('~^[a-z][a-z0-9+.-]*:~i', $value) ||
        strpos($value, '//') === 0
    ) {
        return '';
    }

    // Avoid adding the project folder twice.
    if (
        LG_BASE_PATH !== '' &&
        strpos($value, LG_BASE_PATH . '/') === 0
    ) {
        return $value;
    }

    $value = str_replace('\\', '/', $value);

    // Normalize older paths such as ../../assets/images/product.jpg.
    $value = preg_replace(
        '~^(?:(?:\./|\.\./)+)(?=(?:assets|uploads)/)~',
        '',
        $value
    );

    // Preserve other relative paths whose intended location is unknown.
    if (
        strpos($value, '../') === 0 ||
        strpos($value, './') === 0
    ) {
        return $value;
    }

    // Bare filename (no slashes) = uploaded product image
    if (strpos($value, '/') === false) {
        return lg_url('assets/images/products/' . $value);
    }

    return lg_url($value);
}