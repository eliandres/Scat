<?php
require_once "models/inventario.php";
require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

use App\config\Seguridad;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tokenCode = Seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            echo json_encode(Inventario::obtenerId($_GET['id']));
        } //end if
        else {
            echo json_encode(Inventario::lista($_GET['idm']));
        } //end else
        break;
    case 'POST':
        $tokenCode = Seguridad::validaTokenJwt();
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            $valor = Inventario::insertar(
                $datos->IdMaestroFormato,
                $datos->IdArticulo,
                $datos->IdCatalogoClasificacion,
                $datos->IdCatalogoIdentificador,
                $datos->IdCatalogoHistorial,
                $datos->NombreResponsable,
                $datos->UbicacionActual,
                $datos->RevisionInterno,
                $datos->RevisionExterno,
            );
            if ($valor) {
                echo json_encode(ResponseHttp::status201($valor));
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
            if (Inventario::actualizar($datos->id, $datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
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
            if (Inventario::eliminar($_GET['id'])) {
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