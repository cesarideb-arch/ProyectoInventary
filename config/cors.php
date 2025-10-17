<?php
// ARCHIVO: config/cors.php EN TU FRONTEND

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://apiinventario.idebmexico.com', // Tu backend
    ],

    'allowed_origins_patterns' => [
        'https://.*\.idebmexico\.com',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Cambiar a true
];