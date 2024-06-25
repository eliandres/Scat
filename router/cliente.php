<?php
require_once "models/cliente.php";
require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

use App\config\seguridad;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tokenCode = seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            echo json_encode(Cliente::obtenerId($_GET['id']));
        } //end if
        else {
            echo json_encode(Cliente::lista());
        } //end else
        break;
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        $tokenCode = seguridad::validaTokenJwt();
        if ($datos != NULL) {
            if (Cliente::insertar($datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
                echo json_encode(ResponseHttp::status201());
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
        $datos = json_decode(file_get_contents('php://input'));
        $tokenCode = seguridad::validaTokenJwt();
        if ($datos != NULL) {
            if (Cliente::actualizar($datos->id, $datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
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
        $tokenCode = seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            if (Cliente::eliminar($_GET['id'])) {
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