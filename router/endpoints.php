<?php

namespace App\router;

require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

class Endpoints
{
    public static function endpoints($route)
    {
        $file = __DIR__ . '/../router/' . $route . '.php';

        if (file_exists($file)) {
            require $file;
        } else {
            echo json_encode(ResponseHttp::status400());
        }
    }
}
