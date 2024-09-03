<?php
require_once "conexion/conectar.php";

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

    public static function obtenerId($id_areas)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL obtenerAreasId(?)");
        $stmt->bind_param("i", $id_areas);
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