<?php
/**
 * "core web technologies only, no frameworks/libraries" project rule.
 * Used by modules/cart/payment.php and modules/cart/payment-success.php.
 */
require_once __DIR__ . '/../config/stripe_config.php';

function stripe_api_request($method, $path, $fields = []) {
    $ch = curl_init(STRIPE_API_BASE . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, STRIPE_SECRET_KEY . ':');
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    }
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}
