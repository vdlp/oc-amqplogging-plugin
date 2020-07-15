<?php

declare(strict_types=1);

namespace Vdlp\AmqpLogging\ServiceProviders;

use DateTime;
use Illuminate\Contracts\Config\Repository;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AmqpHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use October\Rain;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Throwable;
use Vdlp\AmqpLogging\Classes\FallbackGroupHandler;

final class ServiceProvider extends Rain\Support\ServiceProvider
{
    /**
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config.php', 'vdlp_amqplogging');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config.php' => config_path('vdlp_amqplogging.php'),
            ], 'vdlp-amqplogging-config');
        }

        /** @var Repository $configuration */
        $configuration = $this->app->make(Repository::class);

        if ($configuration->get('vdlp_amqplogging.enabled')) {
            $this->app->configureMonologUsing(static function (Logger $logger) use ($configuration) {
                $handlers = [];

                try {
                    $connection = new AMQPStreamConnection(
                        $configuration->get('vdlp_amqplogging.parameters.host'),
                        $configuration->get('vdlp_amqplogging.parameters.port'),
                        $configuration->get('vdlp_amqplogging.parameters.login'),
                        $configuration->get('vdlp_amqplogging.parameters.password'),
                        $configuration->get('vdlp_amqplogging.parameters.vhost')
                    );

                    $handler = new AmqpHandler(
                        $connection->channel(),
                        $configuration->get('vdlp_amqplogging.parameters.exchange')
                    );

                    $handler->pushProcessor(static function (array $data) use ($configuration) {
                        $dateTime = $data['datetime'];

                        $data['datetime'] = $dateTime instanceof DateTime
                            ? $dateTime->format('Y-m-d\TH:i:s.uP')
                            : $dateTime;

                        $data['channel'] = $configuration->get('vdlp_amqplogging.parameters.channel');
                        $data['environment'] = $configuration->get('app.env');

                        return $data;
                    });

                    $handlers[] = $handler;
                } catch (Throwable $e) {
                    //
                }

                try {
                    $fallback = new StreamHandler($configuration->get('vdlp_amqplogging.parameters.fallback_path'));
                    $fallback->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON, false));

                    $handlers[] = $fallback;
                } catch (Throwable $e) {
                    //
                }

                $logger->setHandlers([
                    new FallbackGroupHandler($handlers),
                ]);
            });
        }
    }
}
