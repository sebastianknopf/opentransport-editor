<?php

return [
    /**
     * REST API Configuration
     *
     * Default and fallback settings for REST API
     */
    'RestAPI' => [
        'defaultContentType' => 'json',
        'logRequests' => true,
        'logCodes' => '*',
        'CORS' => [
            'allowOrigins' => '*',
            'allowHeaders' => ['Content-Type, Authorization, Accept, Origin'],
            'allowMethods' => ['GET', 'POST', 'OPTIONS'],
            'maxAge' => 2628000
        ]
    ]
];