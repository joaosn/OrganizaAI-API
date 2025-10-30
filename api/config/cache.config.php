<?php

/**
 * Configuração de Cache Redis - SIMPLIFICADO
 * OrganizaAI API - Apenas Sistemas/Planos
 */

return [
    // Redis Connection
    'redis' => [
        'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
    ],

    // Cache apenas para dados estáticos
    'cache' => [
        'prefix' => 'org:',
        'ttls' => [
            'sistemas' => 86400,  // 24 horas
            'planos' => 86400,    // 24 horas
        ],
    ],

    // Rate Limiting (essencial)
    'rate_limiting' => [
        'enabled' => true,
        'limits' => [
            'login' => ['requests' => 5, 'window' => 900],
            'api' => ['requests' => 100, 'window' => 3600],
        ],
    ],
];
