<?php

use Laravel\Sanctum\Sanctum;

return [

    // HoP uses sanctum in pure-token mode (Nuxt frontend sends a bearer
    // token in the Authorization header). Stateful SPA domains stay on
    // localhost in case we ever switch to cookie-based first-party auth.
    'stateful' => explode(',', (string) env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3001,127.0.0.1,127.0.0.1:3001,::1',
        Sanctum::currentApplicationUrlWithPort()
    ))),

    'guard' => ['web'],

    'expiration' => null,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token' => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],

];
