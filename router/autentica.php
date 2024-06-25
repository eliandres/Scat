<?php


require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';
require_once 'models/autentica.php';

use App\config\ResponseHttp;


switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
                  if ($datos != NULL) {
            $token = autentica::login($datos->usuario, $datos->contrasena);
            if ($token != NULL) {
                echo json_encode(ResponseHttp::statusToken200($token));
            } //end if
            else {
                echo json_encode(ResponseHttp::status400());
            } //end else
        } //end if
        else {
            echo json_encode(ResponseHttp::status404());
        } //end else*/
        break;
    default:
        echo json_encode(ResponseHttp::status404());
        break;
}
