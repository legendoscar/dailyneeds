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
        'customer' => [
            'driver' => 'jwt',
            'provider' => 'customer',
        ],
    ],

    'providers' => [
        
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ],        
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'customer' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ],

    'aliases' => [
        'JWTAuth'=>Tymon\JWTAuth\Facades\JWTAuth::class,
    ],
];