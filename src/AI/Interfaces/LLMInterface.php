<?php

namespace AlvinCoded\MtnMomoAi\AI\Interfaces;

interface LLMInterface
{
    /**
     * Analyze transaction data and provide insights
     *
     * @param array $data Transaction data containing details like amount, currency, parties involved
     * @return string Detailed analysis of the transaction
     */
    public function analyze($data);

    /**
     * Detect potential fraudulent activity in transaction data
     *
     * @param array $data Transaction data to analyze for fraud patterns
     * @return array Fraud analysis results with risk level and explanations
     */
    public function detectFraud($data);

    /**
     * Suggest optimal retry strategy for failed transactions
     *
     * @param array $data Failed transaction data including error details
     * @return array Retry strategy with timing and modified parameters
     */
    public function suggestRetryStrategy($data);

    /**
     * Forecast cash flow based on historical transaction data
     *
     * @param array $data Historical transaction data
     * @return array Projected cash flow with trends and predictions
     */
    public function forecastCashFlow($data);

    /**
     * Parse natural language commands into structured transaction data
     *
     * @param string $command Natural language command (e.g., "Send 100 USD to John")
     * @return array Structured command data with action and parameters
     */
    public function parseCommand($command);

    /**
     * Generate comprehensive transaction report
     *
     * @param array $data Transaction data for the reporting period
     * @return array Formatted report with insights and statistics
     */
    public function generateReport($data);

    /**
     * Suggest optimal disbursement timing based on various factors
     *
     * @param float $amount Amount to be disbursed
     * @param string $recipient Recipient identifier
     * @return string Suggested optimal time for disbursement
     */
    public function suggestDisbursementTime($amount, $recipient);

    /**
     * Detect anomalies in transaction patterns
     *
     * @param array $data Transaction data to analyze for anomalies
     * @return array Detected anomalies with severity levels and descriptions
     */
    public function detectAnomalies($data);

    /**
     * Suggest optimal API call timing based on usage patterns
     *
     * @param array $data API usage data and patterns
     * @return array Suggested optimal call times with reasoning
     */
    public function suggestOptimalCallTimes($data);

    /**
     * Provide detailed explanation of error codes in context
     *
     * @param string $errorCode The error code to explain
     * @param array $context Additional context about the error
     * @return array Detailed error explanation with suggested solutions
     */
    public function explainError($errorCode, $context);
}
