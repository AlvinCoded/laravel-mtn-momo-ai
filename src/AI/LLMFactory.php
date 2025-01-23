<?php

namespace AlvinCoded\MtnMomoAi\AI;

use AlvinCoded\MtnMomoAi\AI\Models\ChatGPT;
use AlvinCoded\MtnMomoAi\AI\Models\Claude;
use AlvinCoded\MtnMomoAi\AI\Models\Gemini;
use AlvinCoded\MtnMomoAi\AI\Models\DeepSeek;
use AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface;
use InvalidArgumentException;

/**
 * Factory class for creating Language Learning Model (LLM) instances.
 * 
 * This factory provides a centralized way to create instances of different
 * AI models (ChatGPT, Claude, Gemini) that implement the LLMInterface.
 * Each model is configured using the settings provided in the config file.
 */
class LLMFactory
{
    /**
     * Configuration array containing settings for all supported LLM models.
     *
     * @var array
     */
    protected $config;

    /**
     * Initialize the LLM Factory with configuration settings.
     *
     * The config array should contain separate configurations for each
     * supported model (chatgpt, claude, gemini) with their respective
     * API keys and model-specific settings.
     *
     * @param array $config Configuration array from config/mtn-momo-ai.php
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Create and return an instance of the specified LLM model.
     *
     * This method instantiates the appropriate AI model based on the
     * provided model name. Each model is initialized with its specific
     * configuration from the config array.
     *
     * Supported models:
     * - ChatGPT: OpenAI's GPT model for general analysis and text generation
     * - Claude: Anthropic's Claude model for complex reasoning
     * - Gemini: Google's Gemini model for natural language processing
     * - Deepseek: Deepseek model for natural language processing
     *
     * @param string $model The name of the LLM model to create ('ChatGPT', 'Claude', 'Gemini' or 'Deepseek')
     * @return LLMInterface An instance of the requested LLM model
     * @throws InvalidArgumentException If an unsupported model is requested
     */
    public function create(string $model): LLMInterface
    {
        switch ($model) {
            case 'ChatGPT':
                return new ChatGPT($this->config['chatgpt']);
            case 'Claude':
                return new Claude($this->config['claude']);
            case 'Gemini':
                return new Gemini($this->config['gemini']);
            case 'DeepSeek':
                return new DeepSeek($this->config['deepseek']);
            default:
                throw new InvalidArgumentException("Unsupported LLM model: {$model}");
        }
    }
}
