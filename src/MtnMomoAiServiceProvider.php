<?php

namespace AlvinCoded\MtnMomoAi;

use Illuminate\Support\ServiceProvider;
use AlvinCoded\MtnMomoAi\MtnMomoAi;
use AlvinCoded\MtnMomoAi\Endpoints\Collections;
use AlvinCoded\MtnMomoAi\Endpoints\Disbursements;
use AlvinCoded\MtnMomoAi\Endpoints\Remittances;
use AlvinCoded\MtnMomoAi\AI\LLMFactory;
use AlvinCoded\MtnMomoAi\Console\InstallCommand;

class MtnMomoAiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Merge the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/mtn-momo-ai.php', 'mtn-momo-ai');

        // Bind the main MtnMomoAi class as a singleton
        $this->app->singleton('mtn-momo-ai', function ($app) {
            return new MtnMomoAi(
                new Collections($app['config']['mtn-momo-ai']),
                new Disbursements($app['config']['mtn-momo-ai']),
                new Remittances($app['config']['mtn-momo-ai']),
                new LLMFactory($app['config']['mtn-momo-ai'])
            );
        });

        // Alias for easy facade access
        $this->app->alias('mtn-momo-ai', MtnMomoAi::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Register your install command (optional/custom)
            $this->commands([
                InstallCommand::class,
            ]);

            // Publish the config file
            $this->publishes([
                __DIR__ . '/../config/mtn-momo-ai.php' => config_path('mtn-momo-ai.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mtn-momo-ai', MtnMomoAi::class];
    }
}
