<?php
use core\Router;
$router = new Router();

// ==========================================
// OrganizaAI-API - Rotas
// ==========================================

// ==========================================
// AUTENTICAÇÃO (Views - GET)
// ==========================================
$router->get('/', 'LoginController@index');
$router->get('/login', 'LoginController@index');

// ==========================================
// AUTENTICAÇÃO (API - POST)
// ==========================================
$router->get('/sair', 'LoginController@logout', true);
$router->post('/login', 'LoginController@verificarLogin');

// ==========================================
// DASHBOARD (Views - GET)
// ==========================================
$router->get('/dashboard', 'DashboardController@index', true);

// ==========================================
// DASHBOARD (API - GET)
// ==========================================
$router->get('/api/dashboard/stats', 'DashboardController@obterEstatisticas', true);

// ==========================================
// CLIENTES (Views - GET)
// ==========================================
$router->get('/clientes', 'ClientesController@index', true);

// ==========================================
// CLIENTES (API - GET)
// ==========================================
$router->get('/api/clientes/listar', 'ClientesController@listar', true);

// ==========================================
// SISTEMAS (Views - GET)
// ==========================================
$router->get('/sistemas', 'SistemasController@index', true);

// ==========================================
// SISTEMAS (API - GET)
// ==========================================
$router->get('/api/sistemas/listar', 'SistemasController@listar', true);

// ==========================================
// ASSINATURAS (Views - GET)
// ==========================================
$router->get('/assinaturas', 'AssinaturasController@index', true);

// ==========================================
// ASSINATURAS (API - GET)
// ==========================================
$router->get('/api/assinaturas/listar', 'AssinaturasController@listar', true);


// ==========================================
// FIM DAS ROTAS
// ==========================================
