<?php
require_once "models/maestroFormato.php";
require_once 'config/seguridad.php';
require_once 'config/responseHttp.php';

use App\config\ResponseHttp;

use App\config\Seguridad;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $tokenCode = Seguridad::validaTokenJwt();
        if (isset($_GET['id'])) {
            echo json_encode(MaestroFormato::obtenerId($_GET['id']));
        } //end if
        else {
            echo json_encode(MaestroFormato::lista());
        } //end else
        break;
    case 'POST':
        // Validar el token JWT
        $tokenCode = Seguridad::validaTokenJwt();
        if (!$tokenCode) {
            echo json_encode(ResponseHttp::status404()); // Token no válido
            break;
        }
        // Leer y decodificar los datos JSON del cuerpo de la solicitud
        $datos = json_decode(file_get_contents('php://input'));
        // Verificar si la decodificación fue exitosa y si los datos contienen las propiedades esperadas
        if (json_last_error() === JSON_ERROR_NONE && isset($datos->IdTipoFormato)) {
            // Llamar a la función insertar y capturar el IdMaestroFormato generado
            $idMaestroFormato = MaestroFormato::insertar(
                $datos->IdTipoFormato,
                $datos->IdUnidadEjecutora,
                $datos->IdEntidad,
                $datos->IdDepartamento,
                $datos->IdMunicipio,
                $datos->DocumentoProcesado,
                $datos->FechaDocumentoProcesado,
            );
            if ($idMaestroFormato) {
                echo json_encode(ResponseHttp::status201($idMaestroFormato));
            } else {
                echo json_encode(ResponseHttp::status400()); // Solicitud incorrecta
            }
        } else {
            echo json_encode(ResponseHttp::status400()); // Datos no válidos o falta información
        }
        break;
    case 'PUT':
        $tokenCode = Seguridad::validaTokenJwt();
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos != NULL) {
            if (MaestroFormato::actualizar($datos->id, $datos->nombre, $datos->ap, $datos->am, $datos->fn, $datos->genero)) {
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
            if (MaestroFormato::eliminar($_GET['id'])) {
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