<?php

namespace AlvinCoded\MtnMomoAi\AI\Models;

use AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface;
use Anthropic\Laravel\Facades\Anthropic;

class Claude implements LLMInterface
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function analyze($data)
    {
        return $this->generateResponse("Analyze the following transaction data and provide insights:\n\n" . json_encode($data));
    }

    public function detectFraud($data)
    {
        return $this->generateResponse("Evaluate the following transaction for potential fraud:\n\n" . json_encode($data));
    }

    public function suggestRetryStrategy($data)
    {
        return $this->generateResponse("Suggest a retry strategy for the following failed transaction:\n\n" . json_encode($data));
    }

    public function forecastCashFlow($data)
    {
        return $this->generateResponse("Forecast cash flow based on the following historical data:\n\n" . json_encode($data));
    }

    public function parseCommand($command)
    {
        return $this->generateResponse("Parse the following natural language command into structured data:\n\n" . $command);
    }

    public function generateReport($data)
    {
        return $this->generateResponse("Generate a comprehensive report based on the following transaction data:\n\n" . json_encode($data));
    }

    public function suggestDisbursementTime($amount, $recipient)
    {
        return $this->generateResponse("Suggest an optimal disbursement time for the following transaction:\nAmount: $amount\nRecipient: $recipient");
    }

    public function detectAnomalies($data)
    {
        return $this->generateResponse("Detect any anomalies in the following transaction data:\n\n" . json_encode($data));
    }

    public function suggestOptimalCallTimes($data)
    {
        return $this->generateResponse("Suggest optimal API call times based on the following usage data:\n\n" . json_encode($data));
    }

    public function explainError($errorCode, $context)
    {
        return $this->generateResponse("Explain the following error in the context of an MTN MOMO API transaction:\nError Code: $errorCode\nContext: " . json_encode($context));
    }

    protected function generateResponse($prompt)
    {
        $result = Anthropic::messages()->create([
            'model' => 'claude-3-opus-20240229',
            'max_tokens' => 1024,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $result->content[0]->text;
    }
}
