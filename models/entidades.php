<?php
require_once "conexion/conectar.php";

class Entidades
{

    public static function lista()
    {
        $db = new Conectar();
        $query = "CALL obtenerEntidades()";
        $resultado = $db->query($query);
        $datos = [];
        if ($resultado->num_rows) {
            while ($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'idEntidad' => $row['IdEntidad'],
                    'descripcion' => $row['Descripcion'],
                    'idEstadoRegistro' => $row['IdEstadoRegistro'],
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