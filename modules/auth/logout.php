<?php
require_once __DIR__ . '/../../includes/auth.php';
session_destroy();
header('Location: ' . lg_url('/index.php'));
exit;
