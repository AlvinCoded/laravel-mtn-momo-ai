<?php

namespace AlvinCoded\MtnMomoAi\AI\Interfaces;

interface LLMInterface
{
    public function analyze($data);
    public function detectFraud($data);
    public function suggestRetryStrategy($data);
    public function forecastCashFlow($data);
    public function parseCommand($command);
    public function generateReport($data);
    public function suggestDisbursementTime($amount, $recipient);
    public function detectAnomalies($data);
    public function suggestOptimalCallTimes($data);
    public function explainError($errorCode, $context);
}
