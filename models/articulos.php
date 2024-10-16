<?php
require_once "conexion/conectar.php";
require_once 'config/seguridad.php';

class Articulos
{

    public static function lista()
    {
        $db = new Conectar();
        $query = "CALL ObtenerArticulos()";
        $resultado = $db->query($query);
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'Numero' => $row['Numero'],
                    'DescripcionActivo' => $row['DescripcionActivo'],
                    'Descripcion' => $row['Descripcion'],
                    'Codigo' => $row['CodigoActivo'],
                    'CostoLocal' => $row['CostoLocal'],
                    'CentroCosto' => $row['CentroCosto'],
                    'Modelo' => $row['Modelo'],
                    'IdSerie' => $row['IdSerie'],
                    'FechaAdquisicion' => $row['FechaAdquisicion'],
                    'Municipio' => $row['Municipio'],
                    'Anio' => $row['Anio'],
                    'Departamento' => $row['Departamento'],
                    'Entidad' => $row['Entidad'],
                    'Clasificacion' => $row['Clasificacion'],
                    'Longitud' => $row['Longitud'],
                    'Latitud' => $row['Latitud']
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function obtenerId($idArticulo)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL obtenerAreasId(?)");
        $stmt->bind_param("i", $idArticulo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos = [
                    'idArea ' => $row['IdArea'],
                    'descripcion' => $row['Descripcion'],
                    'formulario' => $row['Formulario'],
                    'idEstadoRegistro' => $row['IdEstadoRegistro'],
                ];
            }
        }
        $stmt->close();
        return $datos;
    }


    public static function obtenerXClasificacion($Cadena)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL ObtenerArticulosXClasificacion(?)");
        $stmt->bind_param("s", $Cadena);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos []= [
                    'Numero' => $row['Numero'],
                    'DescripcionActivo' => $row['DescripcionActivo'],
                    'Descripcion' => $row['Descripcion'],
                    'Codigo' => $row['CodigoActivo'],
                    'CostoLocal' => $row['CostoLocal'],
                    'CentroCosto' => $row['CentroCosto'],
                    'Modelo' => $row['Modelo'],
                    'IdSerie' => $row['IdSerie'],
                    'FechaAdquisicion' => $row['FechaAdquisicion'],
                    'Municipio' => $row['Municipio'],
                    'Anio' => $row['Anio'],
                    'Departamento' => $row['Departamento'],
                    'Entidad' => $row['Entidad'],
                    'Clasificacion' => $row['Clasificacion'],
                    'Longitud' => $row['Longitud'],
                    'Latitud' => $row['Latitud']
                ];
            }
            return $datos;
        }
        return $datos;
    }


    public static function insertar($nombre, $ap, $am, $fn, $genero)
    {
        $db = new Conectar();
        $query = "INSERT INTO clientes (nombre, ap, am, fn, genero)
            VALUES('" . $nombre . "', '" . $ap . "', '" . $am . "', '" . $fn . "', '" . $genero . "')";
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE;
        }
        return FALSE;
    }

    public static function actualizar(
        $Numero,
        $Descripcion,
        $CodigoActivo,
        $Modelo,
        $IdSerie,
        $EntidadEjecutora,
        $FechaAdquisicion,
        $Latitud,
        $Longitud
    ) {
        $db = new Conectar();
        $stmt = $db->prepare("CALL ActualizarArticulos(?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssssss", $Numero, $Descripcion, $CodigoActivo, $Modelo, $IdSerie, $EntidadEjecutora, $FechaAdquisicion, $Latitud, $Longitud);
        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    } //end update

    public static function eliminar($id_cliente)
    {
        $db = new Conectar();
        $query = "DELETE FROM clientes WHERE id=$id_cliente";
        $db->query($query);
        if ($db->affected_rows) {
            return TRUE;
        } //end if
        return FALSE;
    } //end delete

}//end class Cliente