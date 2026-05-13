<?php

return [
    'paths' => [],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [env('APP_URL', 'http://localhost')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'X-CSRF-TOKEN', 'Authorization'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
