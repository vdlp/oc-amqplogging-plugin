<?php

declare(strict_types=1);

return [

    'enabled' => (bool) env('VDLP_AMQPLOGGING_ENABLED'),

    'parameters' => [

        'host' => env('VDLP_AMQPLOGGING_HOST'),
        'port' => env('VDLP_AMQPLOGGING_PORT'),
        'login' => env('VDLP_AMQPLOGGING_LOGIN'),
        'password' => env('VDLP_AMQPLOGGING_PASSWORD'),
        'vhost' => env('VDLP_AMQPLOGGING_VHOST'),
        'exchange' => env('VDLP_AMQPLOGGING_EXCHANGE'),
        'channel' => env('VDLP_AMQPLOGGING_CHANNEL'),

        'fallback_path' => storage_path('logs/amqp.log'),

    ],

];
