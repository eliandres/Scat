<?php
require_once "conexion/conectar.php";

class Departamentos
{

    public static function lista()
    {
        $db = new Conectar();
        $query = "CALL obtenerDepatamentos()";
        $resultado = $db->query($query);
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos []= [
                    'idDepartamento' => $row['IdDepartamento'],
                    'idPermiso' => $row['IdPermiso'],
                    'codigo' => $row['Codigo'],
                    'nombre' => $row['Nombre'],
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
        $stmt = $db->prepare("CALL obtenerAreasId(?)");
        $stmt->bind_param("i", $id_areas);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos = [
                'idDepartamento' => $row['IdDepartamento'],
                    'idPermiso' => $row['IdPermiso'],
                    'codigo' => $row['Codigo'],
                    'nombre' => $row['Nombre'],
                    'idEstadoRegistro' => $row['IdEstadoRegistro'],
                    'fechaCreacion' => $row['FechaCreacion'],
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