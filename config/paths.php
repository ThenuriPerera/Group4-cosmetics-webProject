<?php
/** URL helpers: support WAMP subfolders and a virtual-host document root. */
if (!defined('LG_BASE_PATH')) {
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));
    $scriptFile = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME'] ?? '');
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base = '';
    if (stripos($scriptFile, $projectRoot . '/') === 0) {
        $relative = substr($scriptFile, strlen($projectRoot));
        if ($relative !== '' && substr($scriptName, -strlen($relative)) === $relative) {
            $base = substr($scriptName, 0, -strlen($relative));
        }
    }
    define('LG_BASE_PATH', rtrim($base, '/'));
}

function lg_url($path = '') {
    return LG_BASE_PATH . '/' . ltrim((string) $path, '/');
}

function lg_image_url(array $product) {
    $value = trim((string) ($product['image'] ?? ''));
    if ($value === '') {
        $value = trim((string) ($product['image_url'] ?? ''));
    }
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    if (preg_match('~^[a-z][a-z0-9+.-]*:~i', $value) || strpos($value, '//') === 0) return '';
    if (LG_BASE_PATH !== '' && strpos($value, LG_BASE_PATH . '/') === 0) return $value;
    if (strpos($value, '../') === 0 || strpos($value, './') === 0) return $value;
    return lg_url($value);
}
