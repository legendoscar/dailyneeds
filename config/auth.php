<?php

 return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [

        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],

        'admin' => [
            'driver' => 'jwt',
            'provider' => 'admins',
        ],
        'user' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'store' => [
            'driver' => 'jwt',
            'provider' => 'stores',
        ],
    ],

    'providers' => [
        
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'stores' => [
            'driver' => 'eloquent',
            'model' => App\Models\StoresModel::class,
        ],
    ],

    'aliases' => [
        'JWTAuth'=>Tymon\JWTAuth\Facades\JWTAuth::class,
    ],
];