<?php
use App\config\Seguridad;
require_once "conexion/conectar.php";
require_once 'config/seguridad.php';

class MaestroFormato
{

    public static function lista()
    {
        $db = new Conectar();
        $query = "CALL obtenerMaestroFormatoId(1)";
        $resultado = $db->query($query);
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'idMaestroFormato' => $row['IdMaestroFormato'],
                    'tipoFormato' => $row['TipoF'],
                    'departamento' => $row['Departamento'],
                    'municipio' => $row['Municipio'],
                    'idDepartamento' => $row['IdDepartamento'],
                    'idMunicipio' => $row['IdMunicipio'],
                    'documentoProcesado' => $row['DocumentoProcesado'],
                    'idEstadoRegistro' => $row['IdEstadoRegistro'],
                    'fechaCreacion' => $row['FechaCreacion'],
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function obtenerId($idMaestroFormato)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL obtenerMaestroFormatoId(?)");
        $stmt->bind_param("i", $idMaestroFormato);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'IdMaestroFormato' => $row['IdMaestroFormato'],
                    'TipoFormato' => $row['TipoF'],
                    'Departamento' => $row['Departamento'],
                    'Municipio' => $row['Municipio'],
                    'IdDepartamento' => $row['IdDepartamento'],
                    'IdMunicipio' => $row['IdMunicipio'],
                    'DocumentoProcesado' => $row['DocumentoProcesado'],
                    'IdEstadoRegistro' => $row['IdEstadoRegistro'],
                    'FechaCreacion' => $row['FechaCreacion'],
                    'IdEntidad' => $row['IdEntidad'],
                    'Entidad' => $row['Entidad'],
                    'TotalItem' => $row['TotalItem'],
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function obtenerResumen($idMaestroFormato)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL ObtenerMaestroFormatoResumen(?)");
        $stmt->bind_param("i", $idMaestroFormato);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'IdMaestroFormato' => $row['IdMaestroFormato'],
                    'IdDepartamento' => $row['IdDepartamento'],
                    'Departamento' => $row['Departamento'],
                    'FechaCreacion' => $row['FechaCreacion'],
                    'IdMunicipio' => $row['IdMunicipio'],
                    'TotalItem' => $row['TotalItem'],
                    'IdEntidad' => $row['IdEntidad'],
                    'Entidad' => $row['Entidad'],
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function insertar(
        $IdTipoFormato,
        $IdUnidadEjecutora,
        $IdEntidad,
        $IdDepartamento,
        $IdMunicipio,
        $DocumentoProcesado,
        $FechaDocumentoProcesado
    ) {
        // Crear una instancia de la conexión a la base de datos
        $db = new Conectar();
        $datosJWT = Seguridad::getDataJwt();
        $IpCliente = Seguridad::obtenerIpCliente();
        $FechaCreacion = date('Y-m-d H:i:s');
        $FechaModificacion = date('Y-m-d H:i:s');
        $IdEstadoRegistro = 1;
        $IdUsuarioCreacion = $datosJWT['idUsuario'];
        $DireccionEquipoCreacion = $IpCliente;
        $IdUsuarioModificacion = $datosJWT['idUsuario'];
        $DireccionEquipoModificacion = $IpCliente;

        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $stmt = $db->prepare("CALL InsertarMaestroFormato(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_IdMaestroFormato)");
        if (!$stmt) {
            die("Error en prepare: " . $db->error);
        }
        // Asignar los valores a los parámetros de la consulta
        $stmt->bind_param(
            "iiiiiisissssss",
            $IdTipoFormato,
            $IdUnidadEjecutora,
            $IdEntidad,
            $IdDepartamento,
            $IdMunicipio,
            $DocumentoProcesado,
            $FechaDocumentoProcesado,
            $IdEstadoRegistro,
            $FechaCreacion,
            $IdUsuarioCreacion,
            $DireccionEquipoCreacion,
            $FechaModificacion,
            $IdUsuarioModificacion,
            $DireccionEquipoModificacion
        );
        // Ejecutar la consulta
        if (!$stmt->execute()) {
            die("Error en execute: " . $stmt->error);
        }
        // Obtener el ID generado por la inserción
        $resultado = $db->query("SELECT @p_IdMaestroFormato AS IdMaestroFormato");
        if (!$resultado) {
            die("Error al obtener el IdMaestroFormato: " . $db->error);
        }
        $idMaestroFormato = $resultado->fetch_assoc()['IdMaestroFormato'];
        // Cerrar la declaración
        $stmt->close();
        // Devolver el ID del nuevo registro*/
        return $idMaestroFormato;
    }

    public static function actualizar($IdMaestroFormato, $nombre, $ap, $am, $fn, $genero)
    {
        $db = new Conectar();
        $query = "UPDATE maestroformato SET
            nombre='" . $nombre . "', ap='" . $ap . "', am='" . $am . "', fn='" . $fn . "', genero='" . $genero . "' 
            WHERE IdMaestroFormato=$IdMaestroFormato";
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE;
        } //end if
        return FALSE;
    } //end update

    public static function eliminar($IdMaestroFormato)
    {
        $db = new Conectar();
        $FechaModificacion = date('Y-m-d H:i:s');
        $datosJWT = Seguridad::getDataJwt();
        $IdUsuarioModificacion =  $datosJWT['idUsuario'];
        $stmt = $db->prepare("CALL EliminarMaestroFormato(?,?,?)");
        $stmt->bind_param("isi", $IdMaestroFormato, $FechaModificacion, $IdUsuarioModificacion);
        if ($stmt->execute()) {
            return TRUE;
        } //end if
        return FALSE;
    } //end delete

    public static function procesar($IdMaestroFormato)
    {
        $db = new Conectar();
        $FechaModificacion = date('Y-m-d H:i:s');
        $FechaProcesar = date('Y-m-d H:i:s');
        $datosJWT = Seguridad::getDataJwt();
        $IdUsuarioModificacion =  $datosJWT['idUsuario'];
        $stmt = $db->prepare("CALL ProcesarMaestroFormato(?,?,?,?)");
        $stmt->bind_param("siis", $FechaModificacion, $IdUsuarioModificacion, $IdMaestroFormato, $FechaProcesar);
        if ($stmt->execute()) {
            return TRUE;
        } //end if
        return FALSE;
    }
}//end class Cliente