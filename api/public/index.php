<?php
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if ($origin) {
    // credenciais exigem origem explícita → nunca use '*'
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
} else {
    header("Access-Control-Allow-Origin: *"); // só se NÃO usar withCredentials
}

header("Vary: Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// devolve exatamente o que o browser pediu no preflight
if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
    header("Access-Control-Allow-Headers: " . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
} else {
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
}

// short-circuit para OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}


ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

session_start();

require_once '../vendor/autoload.php';
require_once '../src/routes.php';


$router->run($router->routes);
