<?php

use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

return [
    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'home' => '/dashboard',

    'prefix' => '',

    'middleware' => ['web'],

    'limiters' => [
        'login' => null,
        'two-factor' => null,
    ],

    'views' => true,

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
        ]),
    ],
];
