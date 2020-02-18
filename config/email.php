<?php

return [
    /**
     * Email configuration.
     *
     * By defining transports separately from delivery profiles you can easily
     * re-use transport configuration across multiple profiles.
     *
     * You can specify multiple configurations for production, development and
     * testing.
     *
     * Each transport needs a `className`. Valid options are as follows:
     *
     *  Mail   - Send using PHP mail function
     *  Smtp   - Send using SMTP
     *  Debug  - Do not send the email, just return the result
     *
     * You can add custom transports (or override existing transports) by adding the
     * appropriate file to src/Mailer/Transport. Transports should be named
     * 'YourTransport.php', where 'Your' is the name of the transport.
     */
    'EmailTransport' => [
        'smtp' => [
            'className' => 'Smtp',
            /*
             * The following keys are used in SMTP transports:
             */
            'host' => env('SMTP_HOST', ''),
            'port' => env('SMPT_PORT', 587),
            'timeout' => 30,
            'username' => env('SMTP_USERNAME', ''),
            'password' => env('SMTP_PASSWORD', ''),
            'client' => null,
            'tls' => true,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
    /**
     * Email delivery profiles
     *
     * Delivery profiles allow you to predefine various properties about email
     * messages from your application and give the settings a name. This saves
     * duplication across your application and makes maintenance and development
     * easier. Each profile accepts a number of keys. See `Cake\Mailer\Email`
     * for more information.
     */
    'Email' => [
        'default' => [
            'transport' => 'smtp',
            'from' => env('EMAIL_SENDER', ''),
            'charset' => 'utf-8',
            'headerCharset' => 'utf-8',
            'emailFormat' => 'both'
        ],
    ],
];