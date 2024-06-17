<?php
use App\config\ErrorLog;
use App\config\ResponseHttp;

require './config/error_log.php';
require './config/responseHttp.php';
// Activar el registro de errores
ErrorLog::activateErrorLog();

// Configurar encabezados CORS
ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);

// Obtener la ruta de la URL amigable
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$routes = explode('/api/', $url);
$route = isset($routes[1]) ? $routes[1] : '';

// Manejar las rutas
switch ($route) {
    case 'cliente':
        require './router/cliente.php'; // Incluir el controlador correspondiente para la ruta cliente
        break;
        // Agregar más casos para otras rutas si es necesario
    default:
        echo json_encode(ResponseHttp::status400());
        break;
}
