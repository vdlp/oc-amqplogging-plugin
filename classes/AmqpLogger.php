<?php

declare(strict_types=1);

namespace Vdlp\AmqpLogging\Classes;

use DateTimeInterface;
use Illuminate;
use Illuminate\Contracts\Config\Repository;
use InvalidArgumentException;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\AmqpHandler;
use Monolog\Handler\FallbackGroupHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\LogRecord;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Throwable;

final class AmqpLogger
{
    private Repository $configuration;

    public function __construct(Repository $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(): Logger
    {
        $handlers = [];

        try {
            $connection = new AMQPStreamConnection(
                $this->configuration->get('vdlp_amqplogging.parameters.host'),
                $this->configuration->get('vdlp_amqplogging.parameters.port'),
                $this->configuration->get('vdlp_amqplogging.parameters.login'),
                $this->configuration->get('vdlp_amqplogging.parameters.password'),
                $this->configuration->get('vdlp_amqplogging.parameters.vhost')
            );

            $handler = new AmqpHandler(
                $connection->channel(),
                $this->configuration->get('vdlp_amqplogging.parameters.exchange')
            );

            $handler->pushProcessor(function (mixed $data) {
                if ($data instanceof LogRecord) {
                    $data->extra['channel'] = $this->configuration->get('vdlp_amqplogging.parameters.channel');
                    $data->extra['environment'] = $this->configuration->get('app.env');

                    return $data;
                }

                if (is_array($data)) {
                    $dateTime = $data['datetime'];

                    $data['datetime'] = $dateTime instanceof DateTimeInterface
                        ? $dateTime->format('Y-m-d\TH:i:s.uP')
                        : $dateTime;

                    $data['channel'] = $this->configuration->get('vdlp_amqplogging.parameters.channel');
                    $data['environment'] = $this->configuration->get('app.env');

                    return $data;
                }

                return $data;
            });

            $handlers[] = $handler;
        } catch (Throwable) {
            // @ignoreException
        }

        $fallback = new StreamHandler($this->configuration->get('vdlp_amqplogging.parameters.fallback_path'));
        $fallback->setFormatter(new JsonFormatter(JsonFormatter::BATCH_MODE_JSON));

        $handlers[] = $fallback;

        return new Logger($this->configuration->get('vdlp_amqplogging.parameters.channel'), [
            new FallbackGroupHandler($handlers),
        ]);
    }
}
