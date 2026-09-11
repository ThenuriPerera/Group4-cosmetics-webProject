<?php
/**
 * "core web technologies only, no frameworks/libraries" project rule.
 * Used by modules/cart/payment.php and modules/cart/payment-success.php.
 */
require_once __DIR__ . '/../config/stripe_config.php';

function stripe_api_request($method, $path, $fields = []) {
    if (!defined('STRIPE_SECRET_KEY') || !STRIPE_SECRET_KEY || stripos(STRIPE_SECRET_KEY, 'REPLACE_WITH') !== false) {
        return [
            'error' => [
                'message' => 'Stripe is not configured. Add a valid test secret key in config/stripe_config.php or set STRIPE_SECRET_KEY in your environment.'
            ]
        ];
    }

    $ch = curl_init(STRIPE_API_BASE . $path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, STRIPE_SECRET_KEY . ':');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    }

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return [
            'error' => [
                'message' => 'Stripe request failed: ' . ($curlError ?: 'cURL connection error')
            ],
            'http_status' => $httpStatus,
        ];
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        $message = trim($response) !== '' ? trim($response) : 'Stripe returned an empty or invalid response.';
        return [
            'error' => [
                'message' => $message,
            ],
            'http_status' => $httpStatus,
        ];
    }

    return $decoded;
}
