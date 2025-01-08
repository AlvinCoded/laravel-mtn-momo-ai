<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MTN MOMO API Configuration
    |--------------------------------------------------------------------------
    */
    'api_user' => env('MTN_MOMO_API_USER'),
    'api_key' => env('MTN_MOMO_API_KEY'),
    'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY'),
    'base_url' => env('MTN_MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),
    'default_currency' => env('MTN_MOMO_DEFAULT_CURRENCY', 'EUR'),
    'supported_currencies' => [
        'EUR' => 'Euro',
        'USD' => 'US Dollar',
        'GHS' => 'Ghana Cedi',
        'UGX' => 'Ugandan Shilling',
        'XAF' => 'Central African CFA Franc',
        'XOF' => 'West African CFA Franc',
    ],
    'environment' => env('MTN_MOMO_ENVIRONMENT', 'sandbox'),
    'version' => env('MTN_MOMO_API_VERSION', 'v1_0'),
    'timeout' => env('MTN_MOMO_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | AI Models Configuration
    |--------------------------------------------------------------------------
    */
    'default_llm' => env('DEFAULT_LLM', 'ChatGPT'),

    'chatgpt' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    ],

    'claude' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-3-opus-20240229'),
        'request_timeout' => env('ANTHROPIC_REQUEST_TIMEOUT', 30),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-pro'),
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Feature Configuration
    |--------------------------------------------------------------------------
    */
    'reporting_llm' => env('REPORTING_LLM', 'ChatGPT'),

    /*
    |--------------------------------------------------------------------------
    | Alerting Configuration
    |--------------------------------------------------------------------------
    */
    'alert_email' => env('MTN_MOMO_ALERT_EMAIL'),
];
