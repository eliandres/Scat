<?php

use App\config\ErrorLog;
use App\config\ResponseHttp;

require './config/error_log.php';
require './config/responseHttp.php';
require_once './router/endpoints.php';

// Activar el registro de errores
ErrorLog::activateErrorLog();

// Configurar encabezados CORS
ResponseHttp::headerHttpDev($_SERVER['REQUEST_METHOD']);

// Obtener la ruta de la URL amigable
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Verificar si la URL tiene el prefijo /api
if (strpos($url, '/api/') === 0) {
    // Manejar las rutas de la API
    $route = substr($url, strlen('/api/'));
    \App\router\Endpoints::endpoints($route);
} else {
    // Verificar si el archivo solicitado existe
    $filePath = __DIR__ . '/www/browser' . $url;

    if (file_exists($filePath) && !is_dir($filePath)) {
        // Devolver el archivo solicitado
        $mimeType = mime_content_type($filePath);
        header("Content-Type: $mimeType");
        readfile($filePath);
        exit;
    } else {
        // La URL no tiene el prefijo /api y no es un archivo estático, devolver index.html para que Angular maneje las rutas
        header("Content-Type: text/html");

        // La URL no tiene el prefijo /api y no es un archivo estático, devolver index.html para que Angular maneje las rutas
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        readfile(__DIR__ . '/www/browser/index.html');
        exit;
    }
}
