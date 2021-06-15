<?php

declare(strict_types=1);

namespace Vdlp\AmqpLogging\ServiceProviders;

use October\Rain;

final class ServiceProvider extends Rain\Support\ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'vdlp_amqplogging');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config.php' => config_path('vdlp_amqplogging.php'),
            ], 'vdlp-amqplogging-config');
        }
    }
}
