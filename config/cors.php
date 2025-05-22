<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['GET'],

    // ❗ Apenas seu domínio
    // Caso use porta diferente no dev (ex: Next.js)
    'allowed_origins' => ['http://localhost:8080', 'http://localhost:3000', 'https://portalesnoticias.com.br'],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
