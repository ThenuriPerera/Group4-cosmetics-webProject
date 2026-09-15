<?php
/**
 * Luminé Glow - Editor Dashboard
 *
 * Main navigation for Editor/Admin staff.
 *
 * Product management is handled by:
 * /modules/products/manage.php
 *
 * Order tracking is handled by:
 * /modules/editor/order-tracking.php
 */

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

require_role(['editor', 'admin']);

$pageKey = 'editor/dashboard';

require_once __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../views/editor/dashboard.view.php';
require_once __DIR__ . '/../../includes/footer.php';