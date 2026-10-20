<?php

// Only what differs from the framework's config/filesystems.php
return [

    'disks' => [

        // Laravel 11+ defaults to storage/app/private; downloads live in storage/app/files
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
            'report' => false,
        ],

    ],

];
