<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MTN MOMO API Configuration
    |--------------------------------------------------------------------------
    |
    | This section contains the core configuration for the MTN Mobile Money API.
    | These settings are essential for authenticating and interacting with the
    | MTN MOMO API services including Collections, Disbursements, and Remittances.
    |
    */

    /**
     * The API User ID (UUID v4) used to identify your application.
     * This is automatically generated during installation and used
     * for authentication with the MTN MOMO API.
     * Format: UUID v4 (e.g., '0a60b622-d1a5-4306-8f6b-8551779bf811')
     */
    'api_user' => env('MTN_MOMO_API_USER', null),

    /**
     * The API Key generated for your API User.
     * This is automatically created when setting up your API User
     * and is used for API authentication.
     * Keep this value secure and never expose it publicly.
     */
    'api_key' => env('MTN_MOMO_API_KEY', null),

    /**
     * Your MTN MOMO API Subscription Key.
     * This key is provided when you subscribe to MTN MOMO API products
     * (Collections, Disbursements, or Remittances) in the MTN Developer Portal.
     * Required for all API calls.
     */
    'subscription_key' => env('MTN_MOMO_SUBSCRIPTION_KEY', null),

    /**
     * The base URL for the MTN MOMO API.
     * - Sandbox: 'https://sandbox.momodeveloper.mtn.com'
     * - Production: 'https://momodeveloper.mtn.com'
     */
    'base_url' => env('MTN_MOMO_BASE_URL', 'https://sandbox.momodeveloper.mtn.com'),

    /**
     * The callback host for your application.
     * This URL will receive webhook notifications for transactions.
     * Must be a publicly accessible HTTPS URL in production.
     */
    'callback_host' => env('MTN_MOMO_PROVIDER_CALLBACK_HOST', 'http://localhost'),

    /**
     * The default currency for transactions.
     * This setting determines the default currency used when not explicitly specified.
     * Must be one of the supported currencies listed below.
     */
    'default_currency' => env('MTN_MOMO_DEFAULT_CURRENCY', 'EUR'),

    /**
     * List of supported currencies for MTN MOMO transactions.
     * Each currency must be supported by the MTN MOMO API in your region.
     */
    'supported_currencies' => [
        'EUR' => 'Euro',
        'USD' => 'US Dollar',
        'GHS' => 'Ghana Cedi',
        'UGX' => 'Ugandan Shilling',
        'XAF' => 'Central African CFA Franc',
        'XOF' => 'West African CFA Franc',
    ],

    /**
     * The API environment (sandbox or production).
     * - 'sandbox': For development and testing
     * - 'production': For live transactions
     */
    'environment' => env('MTN_MOMO_ENVIRONMENT', 'sandbox'),

    /**
     * The API version to use.
     * Currently supported version is 'v1_0'.
     * Future versions will be added as they become available.
     */
    'version' => env('MTN_MOMO_API_VERSION', 'v1_0'),

    /**
     * The timeout for API requests in seconds.
     * Adjust based on your needs, but keep in mind MTN MOMO API's own timeout limits.
     */
    'timeout' => env('MTN_MOMO_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | AI Models Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various AI models used to enhance MTN MOMO operations.
    | Each model can be used for transaction analysis, fraud detection,
    | and other AI-powered features.
    |
    */

    /**
     * The default AI model to use when none is specified.
     * Options: 'ChatGPT', 'Claude', 'Gemini'
     */
    'default_llm' => env('DEFAULT_LLM', 'ChatGPT'),

    /**
     * OpenAI (ChatGPT) Configuration
     */
    'chatgpt' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    ],

    /**
     * Anthropic (Claude) Configuration
     */
    'claude' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-3-opus-20240229'),
        'request_timeout' => env('ANTHROPIC_REQUEST_TIMEOUT', 30),
    ],

    /**
     * Google (Gemini) Configuration
     */
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-pro'),
    ],

    /**
     * DeepSeek Configuration
     */
    'deepseek' => [
        'api_key' => env('DEEPSEEK_API_KEY'),
        'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
    ],


    /*
    |--------------------------------------------------------------------------
    | AI Feature Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for AI-enhanced features and reporting capabilities.
    |
    */

    /**
     * The preferred AI model for generating reports.
     * This model will be used specifically for report generation tasks.
     */
    'reporting_llm' => env('REPORTING_LLM', 'ChatGPT'),

    /*
    |--------------------------------------------------------------------------
    | Alerting Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the package's alerting system, including
    | anomaly detection and notification settings.
    |
    */

    /**
     * The email address that will receive alerts about anomalies,
     * potential fraud, and other important notifications.
     */
    'alert_email' => env('MTN_MOMO_ALERT_EMAIL'),
];
