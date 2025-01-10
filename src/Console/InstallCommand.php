<?php

namespace AlvinCoded\MtnMomoAi\Console;

use AlvinCoded\MtnMomoAi\Exceptions\MtnMomoApiException;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * MTN MOMO AI Package Installation Command
 *
 * This command handles the installation and configuration of the MTN MOMO AI package,
 * including environment setup, API user creation, and configuration publishing.
 *
 * @package AlvinCoded\MtnMomoAi\Console
 */
class InstallCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'mtn-momo-ai:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure the MTN MOMO AI package';

     /**
     * Execute the console command.
     *
     * This method initiates the installation process, allowing users to set up
     * their environment variables and publish the configuration file.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('🚀 Welcome to the MTN MOMO AI package installer!');

        if ($this->confirm('🔧 Would you like to set up your environment variables now?', true)) {
            $this->setupEnvironment();
            $this->publishConfig();
            $this->info('✅ Installation complete! Enjoy using MTN MOMO AI!');
        } else {
            $this->info('⏭️ Installation skipped. Run this command again when you\'re ready to configure the package.');
            return;
        }
    }

    /**
     * Set up the environment variables.
     *
     * Configures all necessary environment variables including:
     * - MTN MOMO API credentials
     * - Base URL and environment settings
     * - Default currency
     * - AI model configurations
     * - Alert email settings
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function setupEnvironment()
    {
        if (!File::exists(base_path('.env'))) {
            $this->warn('⚠️ .env file not found. Creating a new one...');
            File::copy(base_path('.env.example'), base_path('.env'));
            $this->info('✅ .env file created successfully!');
        }

        $envFile = base_path('.env');
        $envContents = File::get($envFile);

        // Generate API User UUID
        $apiUserId = Str::uuid()->toString();
        
        $variables = [
            'MTN_MOMO_SUBSCRIPTION_KEY' => $this->secret('🔐 What is your MTN MOMO Subscription Key?'),
            'MTN_MOMO_BASE_URL' => $this->choice('🌐 Which base URL would you like to use?', ['https://sandbox.momodeveloper.mtn.com', 'https://momodeveloper.mtn.com'], 'https://sandbox.momodeveloper.mtn.com'),
            'MTN_MOMO_ENVIRONMENT' => $this->choice('🌍 Which environment are you using?', ['sandbox', 'production'], 'sandbox'),
            'MTN_MOMO_PROVIDER_CALLBACK_HOST' => $this->ask('🔄 What is your callback host? (e.g., https://your-domain.com)', 'http://localhost'),
            'MTN_MOMO_DEFAULT_CURRENCY' => $this->choice('💰 Which currency would you like to use by default?', ['EUR' => 'Euro', 'USD' => 'US Dollar', 'GHS' => 'Ghana Cedi', 'UGX' => 'Ugandan Shilling', 'XAF' => 'Central African CFA Franc', 'XOF' => 'West African CFA Franc'], 'EUR'),
            'DEFAULT_LLM' => $this->choice('🤖 Which AI model would you like to use by default?', ['ChatGPT', 'Claude', 'Gemini'], 'ChatGPT'),
            'OPENAI_API_KEY' => $this->secret('🔑 What is your OpenAI API Key? (Leave blank if not using)'),
            'ANTHROPIC_API_KEY' => $this->secret('🔑 What is your Anthropic API Key? (Leave blank if not using)'),
            'GEMINI_API_KEY' => $this->secret('🔑 What is your Gemini API Key? (Leave blank if not using)'),
            'MTN_MOMO_ALERT_EMAIL' => $this->ask('📧 What email should receive alerts?'),
            'MTN_MOMO_API_USER' => $apiUserId,
        ];

        $apiKey = $this->createApiUser($apiUserId, $variables['MTN_MOMO_SUBSCRIPTION_KEY']);
        $variables['MTN_MOMO_API_KEY'] = $apiKey;

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

    /**
     * Create an API user in the MTN MOMO sandbox.
     *
     * This method performs a three-step process:
     * 1. Creates an API user with the provided UUID
     * 2. Generates an API key for the created user
     * 3. Verifies the user creation by fetching user details
     *
     * @param string $apiUserId The UUID to be used as the API user identifier
     * @param string $subscriptionKey The MTN MOMO subscription key
     * @return string The generated API key
     * @throws MtnMomoApiException If any step in the API user creation process fails
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function createApiUser($apiUserId, $subscriptionKey)
    {
        $client = new Client();
        
        try {
            // Step 1
            $createUserResponse = $client->post(config('mtn-momo-ai.base_url') . '/v1_0/apiuser', [
                'headers' => [
                    'X-Reference-Id' => $apiUserId,
                    'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'providerCallbackHost' => config('mtn-momo-ai.callback_host', 'http://localhost')
                ]
            ]);

            if ($createUserResponse->getStatusCode() !== 201) {
                throw new MtnMomoApiException('Failed to create API user');
            }

            // Step 2
            $createKeyResponse = $client->post(
                config('mtn-momo-ai.base_url') . '/v1_0/apiuser/' . $apiUserId . '/apikey',
                [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            if ($createKeyResponse->getStatusCode() !== 201) {
                throw new MtnMomoApiException('Failed to create API key');
            }

            $apiKey = json_decode($createKeyResponse->getBody()->getContents(), true)['apiKey'];

            // Step 3
            $getUserResponse = $client->get(
                config('mtn-momo-ai.base_url') . '/v1_0/apiuser/' . $apiUserId,
                [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $subscriptionKey,
                        'Content-Type' => 'application/json'
                    ]
                ]
            );

            if ($getUserResponse->getStatusCode() !== 200) {
                throw new MtnMomoApiException('Failed to verify API user');
            }

            return $apiKey;

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new MtnMomoApiException(
                'API User creation failed: ' . $e->getMessage(),
                $e->getCode(),
                $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null
            );
        }
    }

    /**
     * Publish the package configuration file.
     *
     * Publishes the mtn-momo-ai.php configuration file to the application's
     * config directory using Laravel's vendor publishing feature.
     *
     * @return void
     */
    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "AlvinCoded\\MtnMomoAi\\MtnMomoAiServiceProvider",
            '--tag' => 'config'
        ]);
        $this->info('✨ Configuration file published successfully!');
    }
}