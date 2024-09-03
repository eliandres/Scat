<?php
require_once "conexion/conectar.php";

class MaestroFormato
{

    public static function lista()
    {
        $db = new Conectar();
        $query = "CALL obtenerMaestroFormato()";
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

    public static function obtenerId($id_areas)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL obtenerMaestroFormatoId(?)");
        $stmt->bind_param("i", $id_areas);
        $stmt->execute();
        $resultado = $stmt->get_result();
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
                    'idEntidad' => $row['IdEntidad'],
                    'entidad' => $row['Entidad'],
                    'TotalItem' => $row['TotalItem'],
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
        $FechaDocumentoProcesado,

    ) {
        // Crear una instancia de la conexión a la base de datos
        $db = new Conectar();
        $FechaCreacion = date('Y-m-d H:i:s');
        $FechaModificacion = date('Y-m-d H:i:s');
        $IdEstadoRegistro = 1;
        $IdUsuarioCreacion = 1;
        $DireccionEquipoCreacion = ':::fff';
        $IdUsuarioModificacion = 1;
        $DireccionEquipoModificacion = ':::fff';

        // Preparar la consulta SQL para llamar al procedimiento almacenado
        $stmt = $db->prepare("CALL InsertarMaestroFormato(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @p_IdMaestroFormato)");

        // Asignar los valores a los parámetros de la consulta
        $stmt->bind_param(
            "iisiiisississs",
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
        $stmt->execute();

        // Obtener el ID generado por la inserción
        $resultado = $db->query("SELECT @p_IdMaestroFormato AS IdMaestroFormato");
        $idMaestroFormato = $resultado->fetch_assoc()['IdMaestroFormato'];

        // Cerrar la declaración
        $stmt->close();

        // Devolver el ID del nuevo registro
        return $idMaestroFormato;
    }


    public static function actualizar($id_cliente, $nombre, $ap, $am, $fn, $genero)
    {
        $db = new Conectar();
        $query = "UPDATE clientes SET
            nombre='" . $nombre . "', ap='" . $ap . "', am='" . $am . "', fn='" . $fn . "', genero='" . $genero . "' 
            WHERE id=$id_cliente";
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE;
        } //end if
        return FALSE;
    } //end update

    public static function eliminar($idMaestroFormato)
    {
        $db = new Conectar();
        $FechaModificacion = date('Y-m-d H:i:s');
        $IdUsuarioModificacion = 1;
        $stmt = $db->prepare("CALL EliminarMaestroFormato(?,?,?)");
        $stmt->bind_param("isi", $idMaestroFormato, $FechaModificacion, $IdUsuarioModificacion);
        if ($stmt->execute()) {
            return TRUE;
        } //end if
        return FALSE;
    } //end delete

}//end class Cliente