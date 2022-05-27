# Vdlp.AmqpLogging

Extends October CMS logging with an AMQP driver.

- Support for environment specific configuration (using the `.env` file).
- Fallback logging when AMQP connection failed.

## Requirements

- PHP 8.0
- October CMS 2.x (Laravel 6) or October CMS 3.x (Laravel 9)

## Installation

```
composer require vdlp/oc-amqplogging-plugin
```

## Usage

Publish the configuration file.

`php artisan vendor:publish --tag=vdlp-amqplogging-config`

Configure your connection to the AMQP server.

```
VDLP_AMQPLOGGING_HOST = ""
VDLP_AMQPLOGGING_PORT = ""
VDLP_AMQPLOGGING_LOGIN = ""
VDLP_AMQPLOGGING_PASSWORD = ""
VDLP_AMQPLOGGING_VHOST = ""
VDLP_AMQPLOGGING_EXCHANGE = ""
VDLP_AMQPLOGGING_CHANNEL = ""
```

Use the `Vdlp\AmqpLogging\Classes\AmqpLogger` in the `logging.php` configuration:

```
'amqp' => [
    'driver' => 'custom',
    'via' => \Vdlp\AmqpLogging\Classes\AmqpLogger::class,
],
```
