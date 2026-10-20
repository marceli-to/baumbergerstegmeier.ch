<?php

// Only what differs from the framework's config/logging.php
return [

    'channels' => [

        'stack' => [
            'driver' => 'stack',
            'channels' => explode(',', (string) env('LOG_STACK', 'single,slack')),
            'ignore_exceptions' => false,
        ],

        // Everything to the file, whatever LOG_LEVEL says (Slack uses LOG_LEVEL)
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'replace_placeholders' => true,
        ],

    ],

];
