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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // config/services.php

  'gemini' => [
    'key' => env('GEMINI_API_KEY'),
  ],

  'noupia' => [
    'api_key' => env('NOUPIA_API_KEY', 'CdeCvHo5faqh9v.qhxSsnxcyDp34COlz1zyKfd5FbrWb55_m4gO9qXcP8NMlgLXvB59ZLoVJXyaE.o2mwktRyjxnsZgkRP053lz2sMtf3fRB.R7qy3mISQA8OGbCuZwy'),
    'product_key' => env('NOUPIA_PRODUCT_KEY', '1SaY2s9Z.C8WKOZcXhGfehz3K4pQ4f06YyHVaKL7pW0GEGoMKWPkzQQySQ.LZqqA0ABUEtAX2ciXkczLXnhTNqpaeusQ5nI0ySawgqZx1tGqxi1lB2khtnN.7hwDzL1L'),
    'mode' => env('NOUPIA_MODE', 'test'), // 'test' ou 'live'
  ],

];
