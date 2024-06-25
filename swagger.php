<?php
require_once 'autoloader.php';
require_once 'libs/swagger-php/src/functions.php';

if (!class_exists('OpenApi\scan')) {
    die('Error: La clase OpenApi\scan no se cargó correctamente.');
}

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="API documentation"
 * )
 */

/**
 * @OA\Get(
 *     path="/users",
 *     @OA\Response(response=200, description="An example resource")
 * )
 */
class UserController
{
    public function getUsers()
    {
        // Tu código para obtener los usuarios
        return ['user1', 'user2'];
    }
}

header('Content-Type: application/json');
if (!function_exists('\OpenApi\scan')) {
    die('Error: La función \OpenApi\scan no se encontró.');
}

$openapi = '\OpenApi\scan'(__DIR__);
echo $openapi->toJson();
