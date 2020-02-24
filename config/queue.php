<?php

return [
    /**
     * Queue & Jobs Configuration
     *
     * Default and settings for Queue
     */
    'Queue' => [
        'sleeptime' => 10,
        'gcprob' => 10,
        'maxworkers' => 1,
        'exitwhennothingtodo' => false,
        'defaultworkertimeout' => 1800,
        'defaultworkerretries' => 3,
        'workermaxruntime' => 120,
        'workertimeout' => 86400,
        'cleanuptimeout' => 86400
    ]
];