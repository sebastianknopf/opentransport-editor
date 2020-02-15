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
        'defaultworkertimeout' => 1800,
        'defaultworkerretries' => 3,
        'workermaxruntime' => 0,
        'workertimeout' => 0,
        'exitwhennothingtodo' => true,
        'cleanuptimeout' => 2592000,
        'maxworkers' => 1
    ]
];