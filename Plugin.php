<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\AmqpLogging;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name' => 'AMQP Logging',
            'description' => 'Extend October CMS logging with an AMQP driver',
            'author' => 'Van der Let & Partners',
            'icon' => 'icon-leaf',
        ];
    }
    public function register(): void
    {
        $this->app->register(ServiceProviders\ServiceProvider::class);
    }
}
