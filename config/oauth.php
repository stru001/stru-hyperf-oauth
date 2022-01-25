<?php

return [
    'private_key_path'             => BASE_PATH . '/storage/stru-private.key',
    'public_key_path'             => BASE_PATH . '/storage/stru-public.key',
    'private_key'          => env('PRIVATE_KEY',''),
    'public_key'           => env('PUBLIC_KEY',''),
    'encryption_key'       => 'eXJTV0EnDCLTDCEZBWUten/OZ8zmBLd1tCq6uAyd4ro=',

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Model\User::class,
        ],
    ],


    'access_token_expire'  => 'P1D',    //1 day in DateInterval format
    'refresh_token_expire' => 'P1M',    // 1 month in DateInterval format
    'auth_code_expire'     => 'PT10M',  // 10 minutes in DateInterval format

    'grants' => [
//        League\OAuth2\Server\Grant\ClientCredentialsGrant::class => League\OAuth2\Server\Grant\ClientCredentialsGrant::class,
//        League\OAuth2\Server\Grant\PasswordGrant::class => League\OAuth2\Server\Grant\PasswordGrant::class,
        League\OAuth2\Server\Grant\AuthCodeGrant::class => League\OAuth2\Server\Grant\AuthCodeGrant::class,
//        League\OAuth2\Server\Grant\ImplicitGrant::class => League\OAuth2\Server\Grant\ImplicitGrant::class,
//        League\OAuth2\Server\Grant\RefreshTokenGrant::class => League\OAuth2\Server\Grant\RefreshTokenGrant::class
    ],

    'event_listeners' => [],
    'event_listener_providers' => []

];
