<?php

namespace AlvinCoded\MtnMomoAi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * MTN MOMO AI Facade
 *
 * @method static array analyzeTransaction(string $transactionId, string|null $model = null) Analyze a transaction using AI
 * @method static array detectFraud(array $transactionData, string|null $model = null) Detect potential fraud in a transaction
 * @method static array smartRetry(array $failedTransaction, string|null $model = null) Retry a failed transaction with AI-optimized strategy
 * @method static array forecastCashFlow(string $timeframe, string|null $model = null) Forecast cash flow based on historical data
 * @method static array parseNaturalLanguageCommand(string $command, string|null $model = null) Parse natural language commands into API calls
 * @method static array generateReport(string $startDate, string $endDate) Generate AI-enhanced transaction reports
 * @method static array scheduleDisbursement(float $amount, string $recipient, string|null $currency = null) Schedule an AI-optimized disbursement
 * @method static array monitorTransactions() Monitor transactions for anomalies
 * @method static array optimizeApiCalls(string $endpoint) Optimize API call patterns
 * @method static array handleError(string $errorCode, array $context) Handle and explain API errors
 * 
 * @method static array requestToPay(float $amount, string $currency, string $externalId, string $partyId, string $payerMessage, string $payeeNote) Request a payment
 * @method static array getCollectionTransactionStatus(string $referenceId) Get collection transaction status
 * @method static array getAccountBalance() Get account balance
 * @method static array getAccountHolder(string $accountHolderId, string $accountHolderIdType) Get account holder information
 * 
 * @method static array transfer(float $amount, string $currency, string $externalId, string $payee, string $payerMessage, string $payeeNote) Transfer funds
 * @method static array getDisbursementTransactionStatus(string $referenceId) Get disbursement transaction status
 * @method static array getDisbursementAccountBalance() Get disbursement account balance
 * 
 * @method static array remit(float $amount, string $currency, string $externalId, string $payee, string $payerMessage, string $payeeNote) Perform remittance
 * @method static array getRemittanceTransactionStatus(string $referenceId) Get remittance transaction status
 * @method static array getRemittanceAccountBalance() Get remittance account balance
 *
 * @see \AlvinCoded\MtnMomoAi\MtnMomoAi
 * @package AlvinCoded\MtnMomoAi\Facades
 */
class MtnMomoAi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * This function registers the facade accessor for the MTN MOMO AI package.
     * The accessor is used to resolve the underlying instance behind the facade.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mtn-momo-ai';
    }
}
