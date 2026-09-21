<?php
/**
 * Stripe TEST MODE credentials only. Get yours free at https://dashboard.stripe.com/test/apikeys
 */

require_once __DIR__ . '/env.php';

define('STRIPE_SECRET_KEY', lume_env('STRIPE_SECRET_KEY', 'sk_test_'));
define('STRIPE_API_BASE', lume_env('STRIPE_API_BASE', 'https://api.stripe.com/v1'));
