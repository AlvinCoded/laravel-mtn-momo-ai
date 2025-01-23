<?php

namespace AlvinCoded\MtnMomoAi;

use AlvinCoded\MtnMomoAi\Endpoints\Collections;
use AlvinCoded\MtnMomoAi\Endpoints\Disbursements;
use AlvinCoded\MtnMomoAi\Endpoints\Remittances;
use AlvinCoded\MtnMomoAi\AI\LLMFactory;
use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * MTN MOMO AI Integration Package
 *
 * This class provides a comprehensive integration between MTN Mobile Money API
 * and various AI models for enhanced transaction processing and analysis.
 *
 * @package AlvinCoded\MtnMomoAi
 * @author Alvin Panford <panfordalvin@gmail.com>
 */
class MtnMomoAi
{
    /**
     * Trait for making HTTP requests to MTN MOMO API
     */
    use MakesHttpRequests;

    /**
     * @var Collections
     */
    protected $collections;

    /**
     * @var Disbursements
     */
    protected $disbursements;

    /**
     * @var Remittances
     */
    protected $remittances;

    /**
     * @var LLMFactory
     */
    protected $llmFactory;

    /**
     * Constructor for MtnMomoAi
     *
     * @param Collections $collections The Collections API endpoint handler
     * @param Disbursements $disbursements The Disbursements API endpoint handler
     * @param Remittances $remittances The Remittances API endpoint handler
     * @param LLMFactory $llmFactory Factory for creating AI model instances
     */
    public function __construct(Collections $collections, Disbursements $disbursements, Remittances $remittances, LLMFactory $llmFactory) 
    {
        $this->collections = $collections;
        $this->disbursements = $disbursements;
        $this->remittances = $remittances;
        $this->llmFactory = $llmFactory;
    }

    /**
     * Analyze a transaction using AI
     *
     * @param string $transactionId The transaction ID to analyze
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return string Analysis results from the AI model
     */
    public function analyzeTransaction($transactionId, $model = null)
    {
        $transaction = $this->getTransactionDetails($transactionId);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->analyze($transaction);
    }

    /**
     * Detect potential fraud in a transaction
     *
     * @param array $transactionData Transaction data to analyze
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Fraud detection results
     */
    public function detectFraud($transactionData, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->detectFraud($transactionData);
    }

    /**
     * Implement smart retry strategy for failed transactions
     *
     * @param array $failedTransaction Failed transaction data
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Retry attempt results
     */
    public function smartRetry($failedTransaction, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $retryStrategy = $llm->suggestRetryStrategy($failedTransaction);
        return $this->retryTransaction($failedTransaction, $retryStrategy);
    }

    /**
     * Generate cash flow forecast
     *
     * @param string $timeframe Time period to forecast (e.g., '1month', '1year')
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Forecast data
     */
    public function forecastCashFlow($timeframe, $model = null)
    {
        $historicalData = $this->getHistoricalTransactions($timeframe);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->forecastCashFlow($historicalData);
    }

    /**
     * Parse natural language commands into API calls
     *
     * @param string $command Natural language command
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Command execution results
     */
    public function parseNaturalLanguageCommand($command, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $parsedCommand = $llm->parseCommand($command);
        return $this->executeCommand($parsedCommand);
    }

    /**
     * Generate transaction report
     *
     * @param string|\DateTime $startDate Start date for report
     * @param string|\DateTime $endDate End date for report
     * @return array Generated report data
     */
    public function generateReport($startDate, $endDate)
    {
        $transactionData = $this->getTransactions($startDate, $endDate);
        $llm = $this->llmFactory->create(config('mtn-momo-ai.reporting_llm'));
        return $llm->generateReport($transactionData);
    }

    /**
     * Schedule a disbursement with AI-optimized timing
     *
     * @param float $amount Amount to disburse
     * @param string $recipient Recipient identifier
     * @param string|null $currency Optional currency code
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Disbursement results
     */
    public function scheduleDisbursement($amount, $recipient, $currency = null, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $optimalTime = $llm->suggestDisbursementTime($amount, $recipient);
        
        $currency = $currency ?? config('mtn-momo-ai.default_currency');
        
        return $this->disbursements->transfer(
            'ext_'.uniqid(),
            $recipient,
            $amount,
            $currency,
            'Scheduled Transfer',
            'Scheduled at '.$optimalTime
        );
    }

    /**
     * Monitor transactions for anomalies
     *
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array|void Detected anomalies and monitoring results
     */
    public function monitorTransactions($model = null)
    {
        $recentTransactions = $this->getRecentTransactions();
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $anomalies = $llm->detectAnomalies($recentTransactions);
        return $this->alertAnomalies($anomalies);
    }

    /**
     * Optimize API call patterns
     *
     * @param string $endpoint The endpoint to optimize
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Optimization suggestions and timing recommendations
     */
    public function optimizeApiCalls($endpoint, $model = null)
    {
        $usageData = $this->getApiUsageData($endpoint);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->suggestOptimalCallTimes($usageData);
    }

    /**
     * Handle and explain API errors
     *
     * @param string $errorCode The error code received
     * @param array $context Additional context about the error
     * @param string|null $model Optional AI model to use (ChatGPT, Claude, or Gemini)
     * @return array Formatted error response with AI-generated explanation
     */
    public function handleError($errorCode, $context, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $explanation = $llm->explainError($errorCode, $context);
        return $this->formatErrorResponse($explanation);
    }

    /**
     * Request to pay via Collections API
     *
     * @param float $amount Amount to request
     * @param string $currency Currency code
     * @param string $externalId Your transaction reference
     * @param string $partyId Payer's account number
     * @param string $payerMessage Message to payer
     * @param string $payeeNote Note about payment
     * @return array Payment request result
     */
    public function requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote)
    {
        return $this->collections->requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote);
    }

    /**
     * Get Collection transaction status
     *
     * @param string $referenceId Transaction reference ID
     * @return array Transaction status details
     */
    public function getCollectionTransactionStatus($referenceId)
    {
        return $this->collections->getTransactionStatus($referenceId);
    }

    /**
     * Get account balance
     *
     * @return array Account balance information
     */
    public function getAccountBalance()
    {
        return $this->collections->getAccountBalance();
    }

    /**
     * Get account holder information
     *
     * @param string $accountHolderId Account holder's identifier
     * @param string $accountHolderIdType Type of identifier (e.g., 'MSISDN')
     * @return array Account holder details
     */
    public function getAccountHolder($accountHolderId, $accountHolderIdType)
    {
        return $this->collections->getAccountHolder($accountHolderId, $accountHolderIdType);
    }

    /**
     * Transfer funds via Disbursements API
     *
     * @param float $amount Amount to transfer
     * @param string $currency Currency code
     * @param string $externalId Your transaction reference
     * @param string $payee Recipient's account number
     * @param string $payerMessage Message to payer
     * @param string $payeeNote Note about transfer
     * @return array Transfer result
     */
    public function transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)
    {
        return $this->disbursements->transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote);
    }

    /**
     * Get Disbursement transaction status
     *
     * @param string $referenceId Transaction reference ID
     * @return array Transaction status details
     */
    public function getDisbursementTransactionStatus($referenceId)
    {
        return $this->disbursements->getTransactionStatus($referenceId);
    }

    /**
     * Get Disbursement account balance
     *
     * @return array Disbursement account balance information
     */
    public function getDisbursementAccountBalance()
    {
        return $this->disbursements->getAccountBalance();
    }

    /**
     * Send remittance via Remittance API
     *
     * @param float $amount Amount to remit
     * @param string $currency Currency code
     * @param string $externalId Your transaction reference
     * @param string $payee Recipient's account number
     * @param string $payerMessage Message to payer
     * @param string $payeeNote Note about remittance
     * @return array Remittance result
     */
    public function remit($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)
    {
        return $this->remittances->transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote);
    }

    /**
     * Get Remittance transaction status
     *
     * @param string $referenceId Transaction reference ID
     * @return array Transaction status details
     */
    public function getRemittanceTransactionStatus($referenceId)
    {
        return $this->remittances->getTransactionStatus($referenceId);
    }

    /**
     * Get Remittance account balance
     *
     * @return array Remittance account balance information
     */
    public function getRemittanceAccountBalance()
    {
        return $this->remittances->getAccountBalance();
    }

    /**
     * Get transaction details across all products
     *
     * @param string $transactionId Transaction ID to look up
     * @return array Combined transaction details
     */
    protected function getTransactionDetails($transactionId)
    {
        $collectionStatus = $this->collections->getTransactionStatus($transactionId);
        $disbursementStatus = $this->disbursements->getTransactionStatus($transactionId);
        $remittanceStatus = $this->remittances->getTransactionStatus($transactionId);
        
        return array_filter([
            'collection' => $collectionStatus,
            'disbursement' => $disbursementStatus,
            'remittance' => $remittanceStatus
        ]);
    }

    /**
     * Retry a failed transaction with specified strategy
     *
     * @param array $failedTransaction Original failed transaction data
     * @param array $retryStrategy AI-generated retry strategy
     * @return array|null Retry attempt results
     */
    protected function retryTransaction($failedTransaction, $retryStrategy)
    {
        $product = $failedTransaction['product'];
        $amount = $failedTransaction['amount'];
        $currency = $failedTransaction['currency'];
        $externalId = $failedTransaction['externalId'];
        $partyId = $failedTransaction['partyId'];
        $payerMessage = $failedTransaction['payerMessage'];
        $payeeNote = $failedTransaction['payeeNote'];
    
        switch ($product) {
            case 'collection':
                return $this->collections->requestToPay($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote);
            case 'disbursement':
                return $this->disbursements->transfer($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote);
            case 'remittance':
                return $this->remittances->transfer($externalId, $partyId, $amount, $currency, $payerMessage, $payeeNote);
        }
    }

    /**
     * Get historical transaction data
     *
     * @param string $timeframe Time period to fetch (e.g., '1month', '1year')
     * @return array Historical transaction data
     */
    protected function getHistoricalTransactions($timeframe)
    {
        $endDate = now();
        $startDate = $endDate->copy()->sub($timeframe);
        
        $collections = $this->collections->getTransactionStatus($startDate);
        $disbursements = $this->disbursements->getTransactionStatus($endDate);
        $remittances = $this->remittances->getTransactionStatus($endDate);
        
        return array_merge($collections, $disbursements, $remittances);
    }

    /**
     * Execute parsed natural language command
     *
     * @param array $parsedCommand Structured command data from AI parsing
     * @return array|null Command execution results
     */
    protected function executeCommand($parsedCommand)
    {
        switch ($parsedCommand['action']) {
            case 'requestToPay':
                return $this->collections->requestToPay(
                    $parsedCommand['externalId'],
                    $parsedCommand['partyId'],
                    $parsedCommand['amount'],
                    $parsedCommand['currency'],
                    $parsedCommand['payerMessage'],
                    $parsedCommand['payeeNote']
                );
            case 'transfer':
                return $this->disbursements->transfer(
                    $parsedCommand['externalId'],
                    $parsedCommand['partyId'],
                    $parsedCommand['amount'],
                    $parsedCommand['currency'],
                    $parsedCommand['payerMessage'],
                    $parsedCommand['payeeNote']
                );
            case 'remit':
                return $this->remittances->transfer(
                    $parsedCommand['externalId'],
                    $parsedCommand['partyId'],
                    $parsedCommand['amount'],
                    $parsedCommand['currency'],
                    $parsedCommand['payerMessage'],
                    $parsedCommand['payeeNote']
                );
        }
    }

    /**
     * Get transactions within a date range
     *
     * @param \DateTime|string $startDate Start date for transaction fetch
     * @param \DateTime|string $endDate End date for transaction fetch
     * @return array Filtered transactions across all products
     */
    protected function getTransactions($startDate, $endDate)
    {
        $collections = $this->collections->getTransactionStatus($startDate);
        $disbursements = $this->disbursements->getTransactionStatus($endDate);
        $remittances = $this->remittances->getTransactionStatus($endDate);
        
        return array_filter([
            'collections' => $collections,
            'disbursements' => $disbursements,
            'remittances' => $remittances
        ]);
    }

    /**
     * Get recent transactions from the last 7 days
     *
     * @return array Recent transactions across all products
     */
    protected function getRecentTransactions()
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays(7);
        
        return $this->getTransactions($startDate, $endDate);
    }

    /**
     * Process and alert about detected anomalies
     *
     * @param array $anomalies List of detected anomalies
     * @return void
     * @throws \Exception When notification fails
     */
    protected function alertAnomalies($anomalies)
    {
        foreach ($anomalies as $anomaly) {
            Log::warning('Transaction anomaly detected', $anomaly);
            Notification::route('mail', config('mtn-momo-ai.alert_email'))->notify(new \AlvinCoded\MtnMomoAi\Notifications\AnomalyDetectedNotification($anomaly));
        }
    }

    /**
     * Format error response with consistent structure
     *
     * @param string $explanation AI-generated error explanation
     * @return array Structured error response
     */
    protected function formatErrorResponse($explanation)
    {
        return [
            'error' => true,
            'message' => $explanation,
            'timestamp' => now()->toIso8601String(),
        ];
    }
    
    /**
     * Get API usage statistics for an endpoint
     *
     * @param string $endpoint API endpoint to analyze
     * @return array Usage statistics including calls, response times, and error rates
     */
    protected function getApiUsageData($endpoint)
    {
        $baseUrl = config('mtn-momo-ai.base_url');
        $endpointUrl = $baseUrl . '/' . $endpoint;
        
        $transactionData = $this->getTransactionDetails($endpoint);
        $endDate = now();
        $startDate = $endDate->copy()->subDay();
        
        $transactions = $this->getTransactions($startDate, $endDate);
        $failedTransactions = array_filter($transactions, function($transaction) {
            return $transaction['status'] === 'FAILED';
        });
        
        return [
            'endpoint' => $endpointUrl,
            'calls_today' => count($transactions),
            'average_response_time' => $this->calculateAverageResponseTime($transactions),
            'error_rate' => count($failedTransactions) / max(count($transactions), 1),
            'last_transaction_status' => $transactionData
        ];
    }

    /**
     * Calculate average response time for a set of transactions
     *
     * @param array $transactions Array of transactions to analyze
     * @return float|int Average response time in milliseconds
     */
    private function calculateAverageResponseTime($transactions)
    {
        if (empty($transactions)) {
            return 0;
        }
        
        $totalTime = array_reduce($transactions, function($carry, $transaction) {
            return $carry + ($transaction['response_time'] ?? 0);
        }, 0);
        
        return $totalTime / count($transactions);
    }

    
}
