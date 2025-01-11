<?php

namespace AlvinCoded\MtnMomoAi;

use Illuminate\Support\ServiceProvider;
use AlvinCoded\MtnMomoAi\MtnMomoAi;
use AlvinCoded\MtnMomoAi\Endpoints\Collections;
use AlvinCoded\MtnMomoAi\Endpoints\Disbursements;
use AlvinCoded\MtnMomoAi\Endpoints\Remittances;
use AlvinCoded\MtnMomoAi\AI\LLMFactory;
use AlvinCoded\MtnMomoAi\Console\InstallCommand;

/**
 * MTN MOMO AI Service Provider
 *
 * This service provider is responsible for registering and bootstrapping the
 * MTN MOMO AI package within a Laravel application. It handles configuration
 * merging, service registration, and publishes package assets.
 *
 * @package AlvinCoded\MtnMomoAi
 */
class MtnMomoAiServiceProvider extends ServiceProvider
{
    /**
     * Register package services and bindings.
     *
     * This method:
     * - Merges the package configuration with the application's config
     * - Registers the main MtnMomoAi class as a singleton
     * - Sets up facade aliases for easy access
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/mtn-momo-ai.php', 'mtn-momo-ai');

        $this->app->singleton('mtn-momo-ai', function ($app) {
            return new MtnMomoAi(
                new Collections($app['config']['mtn-momo-ai']),
                new Disbursements($app['config']['mtn-momo-ai']),
                new Remittances($app['config']['mtn-momo-ai']),
                new LLMFactory($app['config']['mtn-momo-ai'])
            );
        });

        $this->app->alias('mtn-momo-ai', MtnMomoAi::class);
    }

    /**
     * Bootstrap the package services.
     *
     * This method is called after all services are registered.
     * It handles:
     * - Registration of console commands
     * - Publishing of configuration files
     * - Any other bootstrapping requirements
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                InstallCommand::class
            ]);

            $this->publishes([
                __DIR__ . '/../config/mtn-momo-ai.php' => config_path('mtn-momo-ai.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * Returns an array of service identifiers that this provider makes available
     * to the application. These identifiers can be used for deferred loading
     * of service providers.
     *
     * @return array<string>
     */
    public function provides()
    {
        return ['mtn-momo-ai', MtnMomoAi::class];
    }
}
