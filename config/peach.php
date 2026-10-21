<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Peach Payments Credentials & Environment
    |--------------------------------------------------------------------------
    |
    | Credentials for Peach Payments Checkout V2 / Orchestration Hosted Checkout.
    | Set PEACH_ENVIRONMENT to 'production' for live payments or 'sandbox' for testing.
    |
    */
    'client_id' => env('PEACH_CLIENT_ID', 'test_client_id_playcricket'),
    'client_secret' => env('PEACH_CLIENT_SECRET', 'test_client_secret_playcricket'),
    'merchant_id' => env('PEACH_MERCHANT_ID', 'test_merchant_id_playcricket'),
    'entity_id' => env('PEACH_ENTITY_ID', env('PEACH_MERCHANT_ID', 'test_entity_id_playcricket')),
    'environment' => env('PEACH_ENVIRONMENT', 'sandbox'),
    'webhook_secret' => env('PEACH_WEBHOOK_SECRET', 'test_peach_webhook_secret_playcricket'),

    'currency' => env('PEACH_CURRENCY', 'ZAR'),

    /*
    |--------------------------------------------------------------------------
    | Peach Payments API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'sandbox' => [
            'checkout_initiate' => 'https://testsecure.peachpayments.com/checkout/initiate',
            'checkout_base' => 'https://testsecure.peachpayments.com/checkout',
            'status' => 'https://testsecure.peachpayments.com/checkout/status',
            'oauth' => 'https://sandbox-authentication.peachpayments.com/oauth/token',
        ],
        'production' => [
            'checkout_initiate' => 'https://secure.peachpayments.com/checkout/initiate',
            'checkout_base' => 'https://secure.peachpayments.com/checkout',
            'status' => 'https://secure.peachpayments.com/checkout/status',
            'oauth' => 'https://authentication.peachpayments.com/oauth/token',
        ],
    ],
];
