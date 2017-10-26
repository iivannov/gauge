<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Driver and State Resolver
    |--------------------------------------------------------------------------
    | Drivers: 'runtime', 'file', 'json', 'http'
    | State resolvers:  'url', 'file'
    */
    'driver' => env('GAUGE_DRIVER_DEFAULT', 'runtime'),
    'state' => env('GAUGE_STATE_DEFAULT', 'url'),

    /*
    |--------------------------------------------------------------------------
    | Gauge Driver Configuration
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'file' => [
            'disk' => '' //will default to your laravel configuration
        ],

        'http' => [
            'url' => env('GAUGE_HTTP_URL', ''),
            'token' => env('GAUGE_HTTP_TOKEN', ''),
        ],
    ]
];