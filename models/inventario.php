<?php
require_once "conexion/conectar.php";

class Inventario
{

    public static function lista($n_IdMaestroFormato)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL ObtenerInventario(?)");
        $stmt->bind_param("i", $n_IdMaestroFormato);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'IdMaestroFormato' => $row['IdMaestroFormato'],
                    'IdInventario' => $row['IdInventario'],
                    'Clasificacion' => $row['Clasificacion'],
                    'IdCatalogoClasificacion' => $row['IdCatalogoClasificacion'],
                    'Edentificador' => $row['Edentificador'],
                    'IdCatalogoEdentificador' => $row['IdCatalogoEdentificador'],
                    'Historial' => $row['Historial'],
                    'IdCatalogoHistorial' => $row['IdCatalogoHistorial'],
                    'Numero' => $row['Numero'],
                    'DescripcionActivo' => $row['DescripcionActivo'],
                    'CodigoActivo' => $row['CodigoActivo'],
                    'IdSerie' => $row['IdSerie'],
                    'FechaAdquisicion' => $row['FechaAdquisicion'],
                    'Modelo' => $row['Modelo'],
                    'EntidadEjecutora' => $row['EntidadEjecutora'],
                    'NombreResponsable' => $row['NombreResponsable'],
                    'UbicacionActual' => $row['UbicacionActual'],
                    'Latitud' => $row['Latitud'],
                    'Longitud' => $row['Longitud'],
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function obtenerId($id_cliente)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL obtenerClienteId(?)");
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'ap' => $row['ap'],
                    'am' => $row['am'],
                    'fn' => $row['fn'],
                    'genero' => $row['genero']
                ];
            }
        }
        $stmt->close();
        return $datos;
    }

    public static function insertar(
        $IdMaestroFormato,
        $IdArticulo,
        $IdCatalogoClasificacion,
        $IdCatalogoIdentificador,
        $IdCatalogoHistorial,
        $ResponsableActivo,
        $UbicacionActual,
        $RevisionInterno,
        $RevisionExterno
    ) {
        $db = new Conectar();
        $FechaCreacion = date('Y-m-d H:i:s');  // Genera la fecha actual
        $query = "CALL InsertarInventario(
            '" . $IdMaestroFormato . "',
            '" . $IdArticulo . "',
            '" . $IdCatalogoClasificacion . "',
            '" . $IdCatalogoIdentificador . "',
            '" . $IdCatalogoHistorial . "',
            '" . $ResponsableActivo . "',
            '" . $UbicacionActual . "',
            '" . $RevisionInterno . "',
            '" . $RevisionExterno . "',
            '" . 1 . "',  
            '" . $FechaCreacion . "', 
            '" . 1 . "',
            '" . ':fff' . "',
            '" . $FechaCreacion . "', 
            '" . 1 . "',
            '" . ':fff' . "',
            @IdInventario
        )";
        $db->query($query);
        $result = $db->query("SELECT @IdInventario as IdInventario");
        $row = $result->fetch_assoc();
        if ($row) {
            return $row['IdInventario'];
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