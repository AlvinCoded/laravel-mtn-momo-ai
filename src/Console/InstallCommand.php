<?php

namespace AlvinCoded\MtnMomoAi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'mtn-momo-ai:install';
    protected $description = 'Install and configure the MTN MOMO AI package';

    public function handle()
    {
        $this->info('🚀 Welcome to the MTN MOMO AI package installer!');

        $this->publishConfig();
        $this->setupEnvironment();

        $this->info('✅ Installation complete! Enjoy using MTN MOMO AI!');
        $this->info('📘 Remember to review and complete any missing configurations in your .env file.');
    }

    private function publishConfig()
    {
        if (File::exists(config_path('mtn-momo-ai.php'))) {
            if ($this->confirm('📁 The config file already exists. Do you want to overwrite it?')) {
                $this->publishConfiguration();
                $this->info('✨ Configuration file published successfully!');
            }
        } else {
            $this->publishConfiguration();
            $this->info('✨ Configuration file published successfully!');
        }
    }

    private function publishConfiguration()
    {
        $this->call('vendor:publish', [
            '--provider' => "AlvinCoded\\MtnMomoAi\\MtnMomoAiServiceProvider",
            '--tag' => 'config'
        ]);
    }

    private function setupEnvironment()
    {
        if (!File::exists(base_path('.env'))) {
            $this->warn('⚠️ .env file not found. Creating a new one...');
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('✅ .env file created successfully!');
        }

        if ($this->confirm('🔧 Would you like to set up your environment variables now?', true)) {
            $this->updateEnvironmentFile();
        } else {
            $this->info('⏭️ You can set up your environment variables later in the .env file.');
            $this->displayRequiredEnvVariables();
        }
    }

    private function updateEnvironmentFile()
    {
        $envFile = base_path('.env');
        $envContents = File::get($envFile);

        $variables = [
            'MTN_MOMO_API_USER' => $this->ask('📱 What is your MTN MOMO API User?'),
            'MTN_MOMO_API_KEY' => $this->secret('🔑 What is your MTN MOMO API Key?'),
            'MTN_MOMO_SUBSCRIPTION_KEY' => $this->secret('🔐 What is your MTN MOMO Subscription Key?'),
            'MTN_MOMO_ENVIRONMENT' => $this->choice('🌍 Which environment are you using?', ['sandbox', 'production'], 'sandbox'),
            'MTN_MOMO_DEFAULT_CURRENCY' => $this->choice('💰 Which currency would you like to use by default?', ['EUR' => 'Euro', 'USD' => 'US Dollar', 'GHS' => 'Ghana Cedi', 'UGX' => 'Ugandan Shilling', 'XAF' => 'Central African CFA Franc', 'XOF' => 'West African CFA Franc'], 'USD'),
            'DEFAULT_LLM' => $this->choice('🤖 Which AI model would you like to use by default?', ['ChatGPT', 'Claude', 'Gemini'], 'ChatGPT'),
            'OPENAI_API_KEY' => $this->secret('🔑 What is your OpenAI API Key? (Leave blank if not using)'),
            'ANTHROPIC_API_KEY' => $this->secret('🔑 What is your Anthropic API Key? (Leave blank if not using)'),
            'GEMINI_API_KEY' => $this->secret('🔑 What is your Gemini API Key? (Leave blank if not using)')
        ];

        foreach ($variables as $key => $value) {
            if (!empty($value)) {
                if (preg_match("/^$key=.*/m", $envContents)) {
                    $envContents = preg_replace("/^$key=.*/m", "$key=$value", $envContents);
                } else {
                    $envContents .= "\n$key=$value";
                }
            }
        }

        File::put($envFile, $envContents);
        $this->info('✅ Environment variables have been set successfully!');
    }

    private function displayRequiredEnvVariables()
    {
        $this->info('🔔 Please make sure to set the following environment variables in your .env file:');
        $this->line('MTN_MOMO_API_USER');
        $this->line('MTN_MOMO_API_KEY');
        $this->line('MTN_MOMO_SUBSCRIPTION_KEY');
        $this->line('MTN_MOMO_ENVIRONMENT');
        $this->line('MTN_MOMO_DEFAULT_CURRENCY');
        $this->line('DEFAULT_LLM');
        $this->line('OPENAI_API_KEY (if using ChatGPT)');
        $this->line('ANTHROPIC_API_KEY (if using Claude)');
        $this->line('GEMINI_API_KEY (if using Gemini)');
    }
}
