<?php
require_once "models/municipios.php";
require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

use App\config\Seguridad;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tokenCode = Seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            echo json_encode(Municipios::lista($_GET['id']));
        } //end if
        else {
            echo json_encode(Municipios::lista($_GET['id']));
        } //end else
        break;
    case 'POST':
        $tokenCode = Seguridad::validaTokenJwt();
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            if (Municipios::insertar($datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
                echo json_encode(ResponseHttp::status201("No hay"));
            } //end if
            else {
                echo json_encode(ResponseHttp::status400());
            } //end else
        } //end if
        else {
            echo json_encode(ResponseHttp::status404());
        } //end else
        break;

    case 'PUT':
        $tokenCode = Seguridad::validaTokenJwt();
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            if (Municipios::actualizar($datos->id, $datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
                echo json_encode(ResponseHttp::status200("Registro Actualizado"));
            } //end if
            else {
                echo json_encode(ResponseHttp::status400());
            } //end else
        } //end if
        else {
            echo json_encode(ResponseHttp::status404());
        } //end else
        break;
    case 'DELETE':
        $tokenCode = Seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            if (Municipios::eliminar($_GET['id'])) {
                echo json_encode(ResponseHttp::status200("Registro Eliminado"));
            } //end if
            else {
                echo json_encode(ResponseHttp::status400());
            } //end else
        } //end if
        else {
            echo json_encode(ResponseHttp::status404());
        } //end else
        break;
    default:
        echo json_encode(ResponseHttp::status404());
        break;
}//end while