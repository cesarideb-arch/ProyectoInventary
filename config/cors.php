<?php

return [

    'paths' => ['*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://apiinventario.idebmexico.com', // TU BACKEND
        'https://proyectoinventario.idebmexico.com', // TU FRONTEND
    ],

    'allowed_origins_patterns' => [
        'https://.*\.idebmexico\.com',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];