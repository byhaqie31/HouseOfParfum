<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Comma-separated list of origins via CORS_ALLOWED_ORIGINS (e.g. https://houseofparfum.axelnova.tech).
    // Local dev defaults cover the Nuxt dev server on port 3001 and SSR loopback.
    'allowed_origins' => array_filter(array_map('trim', explode(
        ',',
        env('CORS_ALLOWED_ORIGINS', 'http://localhost:3001,http://127.0.0.1:3001')
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
