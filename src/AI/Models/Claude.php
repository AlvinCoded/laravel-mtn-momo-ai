<?php

namespace AlvinCoded\MtnMomoAi\AI\Models;

use AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface;
use Anthropic\Laravel\Facades\Anthropic;

/**
 * Claude AI Model Implementation for MTN MOMO API
 * 
 * This class implements the LLMInterface to provide AI-powered analysis and insights
 * for MTN Mobile Money transactions using Anthropic's Claude models.
 */
class Claude implements LLMInterface
{
    /** @var array Configuration settings for the Claude model */
    protected $config;

    /**
     * Initialize Claude with configuration
     *
     * @param array $config Configuration array containing model settings
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Analyze transaction data across all MOMO services
     *
     * @param array $data Transaction data including status, amounts, and patterns
     * @return string Analysis results and insights
     */
    public function analyze($data)
    {
        $prompt = "Analyze this MTN MOMO transaction data across collections, disbursements, and remittances. Consider transaction status, amounts, and patterns:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Detect potential fraud in transaction data
     *
     * @param array $data Transaction data to evaluate for fraud indicators
     * @return string Fraud analysis results and risk assessment
     */
    public function detectFraud($data)
    {
        $prompt = "Evaluate this MTN MOMO transaction for potential fraud indicators. Consider transaction type (collection/disbursement/remittance), amount patterns, and account holder behavior:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Suggest retry strategy for failed transactions
     *
     * @param array $data Failed transaction data including error codes and history
     * @return string Recommended retry strategy
     */
    public function suggestRetryStrategy($data)
    {
        $prompt = "Based on this failed MTN MOMO transaction data, suggest an optimal retry strategy considering the transaction type, error codes, and historical success patterns:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Forecast cash flow based on historical data
     *
     * @param array $data Historical transaction data
     * @return string Cash flow forecast and trends analysis
     */
    public function forecastCashFlow($data)
    {
        $prompt = "Analyze this MTN MOMO historical transaction data across collections, disbursements, and remittances to forecast future cash flow patterns and trends:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Parse natural language commands into API requests
     *
     * @param string $command Natural language command to parse
     * @return array|string Structured API request parameters
     */
    public function parseCommand($command)
    {
        $prompt = "Parse this natural language command into a structured MTN MOMO API request format. Consider valid transaction types (collection/disbursement/remittance) and required parameters:\n\n" . $command;
        return $this->generateResponse($prompt);
    }

    /**
     * Generate comprehensive transaction report
     *
     * @param array $data Transaction data to analyze
     * @return string Detailed transaction report
     */
    public function generateReport($data)
    {
        $prompt = "Generate a comprehensive MTN MOMO transaction report analyzing patterns across collections, disbursements, and remittances. Include transaction volumes, success rates, and notable trends:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Suggest optimal disbursement timing
     *
     * @param float $amount Amount to disburse
     * @param string $recipient Recipient identifier
     * @return string Suggested optimal disbursement time
     */
    public function suggestDisbursementTime($amount, $recipient)
    {
        $prompt = "Suggest an optimal disbursement time for this MTN MOMO transfer considering amount: $amount, recipient: $recipient. Consider historical transaction patterns and success rates.";
        return $this->generateResponse($prompt);
    }

    /**
     * Detect transaction anomalies
     *
     * @param array $data Transaction data to analyze for anomalies
     * @return array|string Detected anomalies and their details
     */
    public function detectAnomalies($data)
    {
        $prompt = "Analyze this MTN MOMO transaction data to detect anomalies across collections, disbursements, and remittances. Consider unusual patterns, amounts, frequencies, and account behaviors:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Suggest optimal API call timing
     *
     * @param array $data API usage data and patterns
     * @return string Suggested optimal call times
     */
    public function suggestOptimalCallTimes($data)
    {
        $prompt = "Based on this MTN MOMO API usage data, suggest optimal times for API calls considering success rates, response times, and error patterns:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    /**
     * Explain API errors with context
     *
     * @param string $errorCode The error code to explain
     * @param array $context Additional context about the error
     * @return string Detailed error explanation and resolution steps
     */
    public function explainError($errorCode, $context)
    {
        $prompt = "Explain this MTN MOMO API error in context of the transaction. Provide potential causes and resolution steps:\nError Code: $errorCode\nContext: " . json_encode($context);
        return $this->generateResponse($prompt);
    }

    /**
     * Generate AI response using Anthropic's chat completion
     *
     * @param string $prompt The prompt to send to the AI model
     * @return string Generated response from the AI model
     */
    protected function generateResponse($prompt)
    {
        $result = Anthropic::messages()->create([
            'model' => 'claude-3-opus-20240229',
            'max_tokens' => 500,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $result->content[0]->text;
    }
}
