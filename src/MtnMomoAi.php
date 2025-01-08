<?php

namespace AlvinCoded\MtnMomoAi;

use AlvinCoded\MtnMomoAi\Endpoints\Collections;
use AlvinCoded\MtnMomoAi\Endpoints\Disbursements;
use AlvinCoded\MtnMomoAi\Endpoints\Remittances;
use AlvinCoded\MtnMomoAi\AI\LLMFactory;
use AlvinCoded\MtnMomoAi\Traits\MakesHttpRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class MtnMomoAi
{
    use MakesHttpRequests;

    protected $collections;
    protected $disbursements;
    protected $remittances;
    protected $llmFactory;

    public function __construct(
        Collections $collections,
        Disbursements $disbursements,
        Remittances $remittances,
        LLMFactory $llmFactory
    ) {
        $this->collections = $collections;
        $this->disbursements = $disbursements;
        $this->remittances = $remittances;
        $this->llmFactory = $llmFactory;
    }

    public function analyzeTransaction($transactionId, $model = null)
    {
        $transaction = $this->getTransactionDetails($transactionId);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->analyze($transaction);
    }

    public function detectFraud($transactionData, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->detectFraud($transactionData);
    }

    public function smartRetry($failedTransaction, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $retryStrategy = $llm->suggestRetryStrategy($failedTransaction);
        return $this->retryTransaction($failedTransaction, $retryStrategy);
    }

    public function forecastCashFlow($timeframe, $model = null)
    {
        $historicalData = $this->getHistoricalTransactions($timeframe);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->forecastCashFlow($historicalData);
    }

    public function parseNaturalLanguageCommand($command, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $parsedCommand = $llm->parseCommand($command);
        return $this->executeCommand($parsedCommand);
    }


    public function generateReport($startDate, $endDate)
    {
        $transactionData = $this->getTransactions($startDate, $endDate);
        $llm = $this->llmFactory->create(config('mtn-momo-ai.reporting_llm'));
        return $llm->generateReport($transactionData);
    }

    public function scheduleDisbursement($amount, $recipient, $currency = null, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $optimalTime = $llm->suggestDisbursementTime($amount, $recipient);
        
        // Use provided currency or fall back to default
        $currency = $currency ?? config('mtn-momo-ai.default_currency');
        
        return $this->disbursements->transfer(
            'ext_'.uniqid(),           // externalId
            $recipient,                // partyId
            $amount,                   // amount
            $currency,                 // currency from config or parameter
            'Scheduled Transfer',      // payerMessage
            'Scheduled at '.$optimalTime  // payeeNote
        );
    }



    public function monitorTransactions($model = null)
    {
        $recentTransactions = $this->getRecentTransactions();
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $anomalies = $llm->detectAnomalies($recentTransactions);
        return $this->alertAnomalies($anomalies);
    }

    public function optimizeApiCalls($endpoint, $model = null)
    {
        $usageData = $this->getApiUsageData($endpoint);
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        return $llm->suggestOptimalCallTimes($usageData);
    }

    public function handleError($errorCode, $context, $model = null)
    {
        $llm = $this->llmFactory->create($model ?? config('mtn-momo-ai.default_llm'));
        $explanation = $llm->explainError($errorCode, $context);
        return $this->formatErrorResponse($explanation);
    }

    // Collection API methods
    public function requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote)
    {
        return $this->collections->requestToPay($amount, $currency, $externalId, $partyId, $payerMessage, $payeeNote);
    }
    public function getCollectionTransactionStatus($referenceId)
    {
        return $this->collections->getTransactionStatus($referenceId);
    }
    public function getAccountBalance()
    {
        return $this->collections->getAccountBalance();
    }
    public function getAccountHolder($accountHolderId, $accountHolderIdType)
    {
        return $this->collections->getAccountHolder($accountHolderId, $accountHolderIdType);
    }

    // Disbursement API methods
    public function transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)
    {
        return $this->disbursements->transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote);
    }
    public function getDisbursementTransactionStatus($referenceId)
    {
        return $this->disbursements->getTransactionStatus($referenceId);
    }
    public function getDisbursementAccountBalance()
    {
        return $this->disbursements->getAccountBalance();
    }

    // Remittance API methods
    public function remit($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote)
    {
        return $this->remittances->transfer($amount, $currency, $externalId, $payee, $payerMessage, $payeeNote);
    }
    public function getRemittanceTransactionStatus($referenceId)
    {
        return $this->remittances->getTransactionStatus($referenceId);
    }
    public function getRemittanceAccountBalance()
    {
        return $this->remittances->getAccountBalance();
    }

    // Helper methods

    // Fetch transaction details
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

    // Retry a failed transaction
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

    // Fetch historical transactions
    protected function getHistoricalTransactions($timeframe)
    {
        $endDate = now();
        $startDate = $endDate->copy()->sub($timeframe);
        
        $collections = $this->collections->getTransactionStatus($startDate);
        $disbursements = $this->disbursements->getTransactionStatus($endDate);
        $remittances = $this->remittances->getTransactionStatus($endDate);
        
        return array_merge($collections, $disbursements, $remittances);
    }

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

    // Fetch transactions within a date range
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


    // Fetch recent transactions
    protected function getRecentTransactions()
    {
        $endDate = now();
        $startDate = $endDate->copy()->subDays(7);
        
        return $this->getTransactions($startDate, $endDate);
    }

    // Alert about detected anomalies
    protected function alertAnomalies($anomalies)
    {
        foreach ($anomalies as $anomaly) {
            // Log anomaly
            Log::warning('Transaction anomaly detected', $anomaly);
            
            // Send notification using Laravel's notification system
            Notification::route('mail', config('mtn-momo-ai.alert_email'))->notify(new \AlvinCoded\MtnMomoAi\Notifications\AnomalyDetectedNotification($anomaly));
        }
    }

    protected function formatErrorResponse($explanation)
    {
        return [
            'error' => true,
            'message' => $explanation,
            'timestamp' => now()->toIso8601String(),
        ];
    }
    
    // Fetch API usage data
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
