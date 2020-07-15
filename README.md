# Vdlp.AmqpLogging

Extends October CMS logging with an AMQP driver.

- Support for environment specific configuration (using the `.env` file).
- Fallback logging when AMQP connection failed.

## Installation

```
composer require vdlp/oc-amqplogging-plugin
```

## Usage

Publish the configuration file.

`php artisan vendor:publish --tag=vdlp-amqplogging-config`

Configure your connection to the AMQP server.

```
VDLP_AMQPLOGGING_ENABLED = true
VDLP_AMQPLOGGING_HOST = ""
VDLP_AMQPLOGGING_PORT = ""
VDLP_AMQPLOGGING_LOGIN = ""
VDLP_AMQPLOGGING_PASSWORD = ""
VDLP_AMQPLOGGING_VHOST = ""
VDLP_AMQPLOGGING_EXCHANGE = ""
```

