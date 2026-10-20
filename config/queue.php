<?php

// Only what differs from the framework's config/queue.php
return [

    // failed_jobs has no uuid column
    'failed' => [
        'driver' => env('QUEUE_FAILED_DRIVER', 'database'),
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => 'failed_jobs',
    ],

];
