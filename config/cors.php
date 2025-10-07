<?php

return [

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:8000',
        'http://localhost:80',
        'https://attractive-balance-production.up.railway.app',
        'https://proyectoinventary-production-5036.up.railway.app'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];