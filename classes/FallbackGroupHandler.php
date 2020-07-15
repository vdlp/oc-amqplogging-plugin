<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Vdlp\AmqpLogging\Classes;

use Monolog\Handler\GroupHandler;
use Throwable;

class FallbackGroupHandler extends GroupHandler
{
    public function handle(array $record): bool
    {
        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = $processor($record);
            }
        }

        foreach ($this->handlers as $handler) {
            try {
                $handler->handle($record);
                break;
            } catch (Throwable $e) {
                // What throwable?
            }
        }

        return $this->bubble === false;
    }

    public function handleBatch(array $records): void
    {
        if ($this->processors) {
            $processed = [];
            foreach ($records as $record) {
                foreach ($this->processors as $processor) {
                    $record = $processor($record);
                }
                $processed[] = $record;
            }
            $records = $processed;
        }

        foreach ($this->handlers as $handler) {
            try {
                $handler->handleBatch($records);
                break;
            } catch (Throwable $e) {
                // What throwable?
            }
        }
    }
}
