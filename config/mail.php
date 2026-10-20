<?php

// Only what differs from the framework's config/mail.php
return [

    'mailers' => [

        // SMTP if it works, else the server's sendmail, else the log: the
        // password reset mail must not depend on a mail account (08-deploy.md)
        'failover' => [
            'transport' => 'failover',
            'mailers' => ['smtp', 'sendmail', 'log'],
            'retry_after' => 60,
        ],

    ],

];
