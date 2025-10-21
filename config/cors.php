<?php
// ARCHIVO: config/cors.php EN TU FRONTEND (proyectoinventario.idebmexico.com)

return [

    'paths' => ['*'], // ⬅️ El frontend normalmente no recibe peticiones CORS

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://apiinventario.idebmexico.com', // ⬅️ Esto NO es necesario aquí
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // ⬅️ Normalmente false en frontend

];