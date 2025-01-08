<?php

namespace AlvinCoded\MtnMomoAi\AI\Models;

use AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface;
use OpenAI\Factory as OpenAIFactory;

class ChatGPT implements LLMInterface
{
    protected $client;
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;

        // Initialize the OpenAI client using the Factory pattern
        $factory = new OpenAIFactory();

        // Optionally set the organization if available
        if (! empty($config['organization'])) {
            $factory->withOrganization($config['organization']);
        }

        // Build the final OpenAI client instance
        $this->client = $factory->withApiKey($config['api_key'])->make();
    }

    public function analyze($data)
    {
        $prompt = "Analyze the following transaction data and provide insights:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function detectFraud($data)
    {
        $prompt = "Evaluate the following transaction for potential fraud:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function suggestRetryStrategy($data)
    {
        $prompt = "Suggest a retry strategy for the following failed transaction:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function forecastCashFlow($data)
    {
        $prompt = "Forecast cash flow based on the following historical data:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function parseCommand($command)
    {
        $prompt = "Parse the following natural language command into structured data:\n\n" . $command;
        return $this->generateResponse($prompt);
    }

    public function generateReport($data)
    {
        $prompt = "Generate a comprehensive report based on the following transaction data:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function suggestDisbursementTime($amount, $recipient)
    {
        $prompt = "Suggest an optimal disbursement time for the following transaction:\nAmount: $amount\nRecipient: $recipient";
        return $this->generateResponse($prompt);
    }

    public function detectAnomalies($data)
    {
        $prompt = "Detect any anomalies in the following transaction data:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function suggestOptimalCallTimes($data)
    {
        $prompt = "Suggest optimal API call times based on the following usage data:\n\n" . json_encode($data);
        return $this->generateResponse($prompt);
    }

    public function explainError($errorCode, $context)
    {
        $prompt = "Explain the following error in the context of an MTN MOMO API transaction:\nError Code: $errorCode\nContext: " . json_encode($context);
        return $this->generateResponse($prompt);
    }

    protected function generateResponse($prompt)
    {
        $response = $this->client->chat()->create([
            'model' => $this->config['model'] ?? 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI assistant specialized in analyzing MTN MOMO transactions and providing insights.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 400,
        ]);

        return $response['choices'][0]['message']['content'] ?? '';
    }
}
