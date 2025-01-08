# Laravel MTN MOMO AI 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/AlvinCoded/laravel-mtn-momo-ai.svg?style=flat-square)](https://packagist.org/packages/AlvinCoded/laravel-mtn-momo-ai)
[![Total Downloads](https://img.shields.io/packagist/dt/AlvinCoded/laravel-mtn-momo-ai.svg?style=flat-square)](https://packagist.org/packages/AlvinCoded/laravel-mtn-momo-ai)
[![License](https://img.shields.io/packagist/l/AlvinCoded/laravel-mtn-momo-ai.svg?style=flat-square)](https://packagist.org/packages/AlvinCoded/laravel-mtn-momo-ai)
<!-- [![Sponsor](https://img.shields.io/static/v1?label=Sponsor&message=%E2%9D%A4&logo=GitHub&color=ff69b4)](https://github.com/sponsors/AlvinCoded) -->

A powerful Laravel package that integrates MTN Mobile Money API with AI capabilities, providing intelligent transaction analysis, fraud detection, and more!

## Features 🌟

- 💳 Seamless integration with MTN MOMO API (Collections, Disbursements, Remittances)
- 🧠 AI-powered transaction analysis using multiple LLM models (ChatGPT, Claude, Gemini)
- 🕵️ Intelligent fraud detection
- 📊 Cash flow forecasting
- 🔄 Smart retry mechanisms for failed transactions
- 📝 Natural language command parsing
- 📈 Automated reporting
- ⏱️ Smart disbursement scheduling
- 🚨 Anomaly detection in transactions
- 🔧 API call optimization

## Prerequisites 🔑

Before getting started with the MTN MOMO AI package, you'll need:

### MTN MOMO API Requirements
1. **MTN MOMO Developer Account**
   - Sign up at [MTN MOMO Developer Portal](https://momodeveloper.mtn.com/signup)
   - Complete the verification process
   - Store your API credentials safely

2. **API Subscription**
   - Subscribe to one or more MTN MOMO products:
     - Collections
     - Disbursements
     - Remittances
   - Get your Subscription Key from your [developer profile](https://momodeveloper.mtn.com/developer)

### AI Model Requirements
You'll need at least one of these API keys:
- OpenAI API Key (for ChatGPT)
- Anthropic API Key (for Claude)
- Google Gemini API Key (for Gemini)

### System Requirements
- PHP 8.1 or higher
- Laravel 9.x|10.x|11.x
- Composer

### Environment Setup
Make sure your server/hosting environment:
- Allows outbound HTTPS connections
- Has sufficient memory for AI operations
- Supports background job processing (for notifications)
 

## Installation 📦

> **Requires [PHP 8.1+](https://php.net/releases/)**

You can install the package via composer:

```bash
composer require alvincoded/laravel-mtn-momo-ai
```

After installation, run the package's installation command:

```bash
php artisan mtn-momo-ai:install
```

This command will guide you through the setup process, including publishing the configuration file and setting up your environment variables.

## Configuration ⚙️

The package configuration file will be published to `config/mtn-momo-ai.php`. You can modify the settings there or use environment variables in your `.env` file.

Key configurations include:

- MTN MOMO API credentials
- AI model settings (ChatGPT, Claude, Gemini)
- Default LLM selection
- Alerting settings

### Environment Variables

```bash
# MTN MOMO API Configuration
MTN_MOMO_API_USER=your_api_user
MTN_MOMO_API_KEY=your_api_key
MTN_MOMO_SUBSCRIPTION_KEY=your_subscription_key
MTN_MOMO_BASE_URL=https://sandbox.momodeveloper.mtn.com
MTN_MOMO_ENVIRONMENT=sandbox
MTN_MOMO_DEFAULT_CURRENCY=USD

# AI Model API Keys
OPENAI_API_KEY=your_openai_key
ANTHROPIC_API_KEY=your_anthropic_key
GEMINI_API_KEY=your_gemini_key

# Default LLM Configuration
DEFAULT_LLM=ChatGPT
REPORTING_LLM=ChatGPT

# Alerting Configuration
MTN_MOMO_ALERT_EMAIL=alerts@example.com
```


## Usage 🛠️

### Basic Usage

```php
use AlvinCoded\MtnMomoAi\Facades\MtnMomoAi;

// Analyze a transaction
$analysis = MtnMomoAi::analyzeTransaction('transaction123');

// Request to pay
$result = MtnMomoAi::requestToPay(100, 'GHS', 'ext123', 'party123', 'Payment', 'Note');

// Transfer (Disbursement)
$result = MtnMomoAi::transfer(100, 'GHS', 'ext123', 'party123', 'Payment', 'Note');

// Remittance
$result = MtnMomoAi::remit(100, 'GHS', 'ext123', 'party123', 'Payment', 'Note');
```

### AI-Enhanced Features

```php
// Detect fraud
$fraudAnalysis = MtnMomoAi::detectFraud($transactionData);

// Forecast cash flow
$forecast = MtnMomoAi::forecastCashFlow('1month');

// Parse natural language command
$result = MtnMomoAi::parseNaturalLanguageCommand('Send 100 GHS to John Doe');

// Generate report
$report = MtnMomoAi::generateReport($startDate, $endDate);

// Detect anomalies
$anomalies = MtnMomoAi::monitorTransactions();
```

### Currency Configuration

```php
// Using default currency
MtnMomoAi::scheduleDisbursement(100, 'recipient123');

// Specifying a different currency
MtnMomoAi::scheduleDisbursement(100, 'recipient123', 'GHS');
```

### Transaction Analysis

```php
// Detailed transaction analysis
$analysis = MtnMomoAi::analyzeTransaction('transaction123');
// Returns AI-powered insights about the transaction

// Fraud detection with custom parameters
$fraudAnalysis = MtnMomoAi::detectFraud([
    'amount' => 1000,
    'currency' => 'GHS',
    'recipient' => 'user123',
    'timestamp' => now(),
    'location' => 'GH'
]);
```

### Smart Retry Mechanism

```php
// Retry failed transaction with AI-optimized strategy
$retryResult = MtnMomoAi::smartRetry([
    'product' => 'collection',
    'amount' => 100,
    'currency' => 'GHS',
    'externalId' => 'ext123',
    'partyId' => 'party123',
    'payerMessage' => 'Payment',
    'payeeNote' => 'Note'
]);
```

### Error Handling

The package includes comprehensive error handling:

```php
try {
    $result = MtnMomoAi::requestToPay(100, 'GHS', 'ext123', 'party123', 'Payment', 'Note');
} catch (\AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException $e) {
    // Handle API-specific errors
    $errorDetails = $e->getResponseBody();
} catch (\Exception $e) {
    // Handle general errors
}
```

### AI Model Selection
```php
// Use default model from config
$analysis = MtnMomoAi::analyzeTransaction('transaction123');

// Specify a particular model
$analysis = MtnMomoAi::analyzeTransaction('transaction123', 'Claude');
$fraudCheck = MtnMomoAi::detectFraud($transactionData, 'Gemini');
$forecast = MtnMomoAi::forecastCashFlow('1month', 'ChatGPT');

```

## API Reference 📚

### MTN MOMO API Methods

- `requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote)`
- `getCollectionTransactionStatus($referenceId)`
- `getAccountBalance()`
- `getAccountHolder($accountHolderId, $accountHolderIdType)`
- `transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)`
- `getDisbursementTransactionStatus($referenceId)`
- `remit($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)`
- `getRemittanceTransactionStatus($referenceId)`

### AI-Enhanced Methods

- `analyzeTransaction($transactionId)`
- `detectFraud($transactionData)`
- `smartRetry($failedTransaction)`
- `forecastCashFlow($timeframe)`
- `parseNaturalLanguageCommand($command)`
- `generateReport($startDate, $endDate)`
- `scheduleDisbursement($amount, $recipient)`
- `monitorTransactions()`
- `optimizeApiCalls($endpoint)`
- `handleError($errorCode, $context)`

## Testing 🧪

To run the package tests:

```bash
composer test
```
Here's the section to add to your README.md for sponsorship:

<!-- ## Support Us 💖

Creating and maintaining open-source projects requires significant time and effort. Your support will help enhance the project and enable further contributions to the PHP community.

Sponsorship can be made through the GitHub Sponsors program. Just click the `Sponsor` button at the top of this repository. Any amount is greatly appreciated, even a contribution as small as $1 can make a big difference and will go directly towards developing and improving this package.

Thank you for considering sponsoring. Your support truly makes a difference! -->

## Contributing 🤝

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security 🔒

If you discover any security-related issues, don't hesitate to use the issue tracker.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits 👏

- Author: [Alvin Panford](https://github.com/AlvinCoded)
- OpenAI PHP by [Nuno Maduro](https://github.com/openai-php/laravel)
- Anthropic Laravel by [Mozex](https://github.com/mozex/anthropic-laravel)
- **Gemini PHP** for Laravel by [Fatih AYDIN](https://github.com/aydinfatih/)

## License 📄

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Support 💬

For support, please open an issue in the GitHub repository.
