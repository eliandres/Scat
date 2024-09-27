<?php
require_once "models/articulos.php";
require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

use App\config\Seguridad;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tokenCode = Seguridad::validaTokenJwt();
        if (!$tokenCode) {
            echo json_encode(ResponseHttp::status404()); // Token no válido
            break;
        }
        if (isset($_GET['id'])) {
            echo json_encode(Articulos::obtenerId($_GET['id']));
        } //end if
        else if ($_GET['Cadena']) {
            echo json_encode(Articulos::obtenerXClasificacion($_GET['Cadena']));
        } else {
            echo json_encode(Articulos::lista());
        } //end else
        break;
    case 'POST':
        $tokenCode = Seguridad::validaTokenJwt();
        if (!$tokenCode) {
            echo json_encode(ResponseHttp::status404()); // Token no válido
            break;
        }
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            if (Articulos::insertar($datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
                echo json_encode(ResponseHttp::status201(""));
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
        if (!$tokenCode) {
            echo json_encode(ResponseHttp::status404()); // Token no válido
            break;
        }

        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            $valor = Articulos::actualizar(
                $datos->IdArticulo,
                $datos->Descripcion,
                $datos->CodigoIdentificador,
                $datos->Modelo,
                $datos->IdSerie,
                $datos->Entidad,
                $datos->FechaEntrega,
                $datos->Latitude,
                $datos->Longitude
            );
            if ($valor) {
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
            if (Articulos::eliminar($_GET['id'])) {
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