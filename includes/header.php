<?php
// Compatibility entry point; shared HTML lives in layouts/header.php.
require_once __DIR__ . '/auth.php';
require __DIR__ . '/../config/pages.php';
if (!defined('LG_VIEW')) define('LG_VIEW', true);
require __DIR__ . '/../layouts/header.php';
