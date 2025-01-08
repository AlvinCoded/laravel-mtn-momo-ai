<?php

namespace AlvinCoded\MtnMomoAi\AI;

use AlvinCoded\MtnMomoAi\AI\Models\ChatGPT;
use AlvinCoded\MtnMomoAi\AI\Models\Claude;
use AlvinCoded\MtnMomoAi\AI\Models\Gemini;
use AlvinCoded\MtnMomoAi\AI\Interfaces\LLMInterface;
use InvalidArgumentException;

class LLMFactory
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function create(string $model): LLMInterface
    {
        switch ($model) {
            case 'ChatGPT':
                return new ChatGPT($this->config['chatgpt']);
            case 'Claude':
                return new Claude($this->config['claude']);
            case 'Gemini':
                return new Gemini($this->config['gemini']);
            default:
                throw new InvalidArgumentException("Unsupported LLM model: {$model}");
        }
    }
}
