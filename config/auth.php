<?php

use Cake\Routing\Router;

return [
    /**
     * Authentication & Authorization Configuration
     *
     */
    'Authentication' => [
        'unauthenticatedUrl' => Router::url('/admin/login'),
        'queryParam' => 'redirect'
    ],
    'Authorization' => [
        'requireAuthorizationCheck' => false
    ]
];
