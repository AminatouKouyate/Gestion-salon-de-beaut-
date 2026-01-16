<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */
    'guards' => [

        // ADMIN
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // CLIENT
        'clients' => [
            'driver' => 'session',
            'provider' => 'clients',
        ],

        // EMPLOYÉ
        'employees' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [

        // Admins (table users)
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Clients
        'clients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Client::class,
        ],

        // Employés
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'clients' => [
            'provider' => 'clients',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'employees' => [
            'provider' => 'employees',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,
];
