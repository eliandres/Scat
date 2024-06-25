<?php

use App\config\ErrorLog;
use App\config\ResponseHttp;

// Bloquear acceso al archivo .env
if (isset($_SERVER['REQUEST_URI']) && basename($_SERVER['REQUEST_URI']) === '.env') {
    http_response_code(403);
    echo "Access denied";
    exit;
}

require './config/error_log.php';
require './config/responseHttp.php';
require_once './router/endpoints.php';
// Activar el registro de errores
ErrorLog::activateErrorLog();

// Configurar encabezados CORS
ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);

// Obtener la ruta de la URL amigable
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routes = explode('/api/', $url);
$route = isset($routes[1]) ? $routes[1] : '';

// Manejar las rutas
\App\router\endpoints::endpoints($route);
