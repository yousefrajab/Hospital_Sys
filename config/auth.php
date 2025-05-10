<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'doctor' => [
            'driver' => 'session',
            'provider' => 'doctors',
        ],

        'ray_employee' => [
            'driver' => 'session',
            'provider' => 'ray_employees',
        ],

        'laboratorie_employee' => [
            'driver' => 'session',
            'provider' => 'laboratorie_employees',
        ],

        'patient' => [
            'driver' => 'session',
            'provider' => 'patients',
        ],


        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'doctors' => [
            'driver' => 'eloquent',
            'model' => App\Models\Doctor::class,
        ],

        'ray_employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\RayEmployee::class,
        ],

        'laboratorie_employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\LaboratorieEmployee::class,
        ],

        'patients' => [
            'driver' => 'eloquent',
            'model' => App\Models\Patient::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admins' => [ // اسم الـ broker
            'provider' => 'admins', // يشير إلى الـ provider الخاص بالأدمن
            'table' => 'password_resets', // اسم جدول رموز إعادة التعيين
            'expire' => 60,
            'throttle' => 60,
        ],

        // ** يجب عليك إضافة brokers لبقية الـ Guards بنفس الطريقة **
        'doctors' => [
            'provider' => 'doctors', // يشير إلى provider 'doctors'
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'patients' => [
            'provider' => 'patients',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'ray_employees' => [
            'provider' => 'ray_employees',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'laboratorie_employees' => [
            'provider' => 'laboratorie_employees',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],




    'password_timeout' => 10800,

];
