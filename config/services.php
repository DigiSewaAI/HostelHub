<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    */
    'esewa' => [
        'merchant_id' => env('ESEWA_MERCHANT_ID'),
        'secret_key' => env('ESEWA_SECRET_KEY'),
        'verify_url' => env('ESEWA_VERIFY_URL', 'https://rc-epay.esewa.com.np/api/epay/transaction/status/'),
    ],

    'khalti' => [
        'live_secret_key' => env('KHALTI_LIVE_SECRET_KEY'),
        'test_secret_key' => env('KHALTI_TEST_SECRET_KEY'),
        'verify_url' => 'https://khalti.com/api/v2/payment/verify/',
    ],

    'bank' => [
        'account_number' => env('BANK_ACCOUNT_NUMBER'),
        'account_name' => env('BANK_ACCOUNT_NAME'),
        'name' => env('BANK_NAME'),
        'branch' => env('BANK_BRANCH', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Configuration
    |--------------------------------------------------------------------------
    |
    | This section contains default image paths used throughout the application.
    |
    */

    'images' => [
        'default_gallery_thumbnail' => 'images/default-gallery-thumbnail.jpg',
    ],

];
