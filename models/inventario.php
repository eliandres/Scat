<?php
require_once "conexion/conectar.php";
require_once 'config/seguridad.php';

use App\config\Seguridad;

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
                    'IdCatalogoIdentificador' => $row['IdCatalogoIdentificador'],
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
                    'FechaInicial' => $row['FechaInicial'],
                    'FechaFinalizacion' => $row['FechaFinalizacion'],
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function obtenerId($id_cliente)
    {
        $db = new Conectar();
        $stmt = $db->prepare("CALL ObtenerInventarioId(?)");
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
        $FechaInicial,
        $FechaFinalizacion,
        $RevisionInterno,
        $RevisionExterno
    ) {
        $db = new Conectar();
        $datosJWT = Seguridad::getDataJwt();
        $IpCliente = Seguridad::obtenerIpCliente();
        $IdEstadoRegistro = 1;
        $IdUsuarioCreacion = $datosJWT['idUsuario'];
        $DireccionEquipoCreacion = $IpCliente;
        $FechaCreacion = date('Y-m-d H:i:s');  // Genera la fecha actual
        // Verifica y convierte la fecha inicial
        $fechaInicialDateTime = DateTime::createFromFormat('d/m/Y', $FechaInicial);
        if ($fechaInicialDateTime !== false) {
            // Convierte la fecha al formato Y-m-d para guardarla en la base de datos
            $FechaInicial = $fechaInicialDateTime->format('Y-m-d');
        } else {
            die("Error: Formato de Fecha de Finalización inválido.");
        }

        // Verifica y convierte la fecha de finalización
        $fechaFinalizacionDateTime = DateTime::createFromFormat('d/m/Y', $FechaFinalizacion);
        if ($fechaFinalizacionDateTime !== false) {
            // Convierte la fecha al formato Y-m-d para guardarla en la base de datos
            $FechaFinalizacion = $fechaFinalizacionDateTime->format('Y-m-d');
        } else {
            die("Error: Formato de Fecha de Finalización inválido.");
        }

        $query = "CALL InsertarInventario(
            '" . $IdMaestroFormato . "',
            '" . $IdArticulo . "',
            '" . $IdCatalogoClasificacion . "',
            '" . $IdCatalogoIdentificador . "',
            '" . $IdCatalogoHistorial . "',
            '" . $ResponsableActivo . "',
            '" . $UbicacionActual . "',
            '" . $FechaInicial . "',
            '" . $FechaFinalizacion . "',
            '" . $RevisionInterno . "',
            '" . $RevisionExterno . "',
            '" . $IdEstadoRegistro . "',  
            '" . $FechaCreacion . "', 
            '" . $IdUsuarioCreacion . "',
            '" . $DireccionEquipoCreacion . "',
            '" . $FechaCreacion . "', 
            '" . $IdUsuarioCreacion . "',
            '" . $DireccionEquipoCreacion . "',
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

    public static function actualizar(
        $IdInventario,
        $IdMaestroFormato,
        $IdArticulo,
        $IdCatalogoClasificacion,
        $IdCatalogoIdentificador,
        $IdCatalogoHistorial,
        $ResponsableActivo,
        $UbicacionActual,
        $FechaInicial,
        $FechaFinalizacion,
        $RevisionInterno,
        $RevisionExterno
    ) {
        $db = new Conectar();
        $datosJWT = Seguridad::getDataJwt();
        $IpCliente = Seguridad::obtenerIpCliente();
        // Parámetros estáticos
        $IdEstadoRegistro = 1;
        $IdUsuarioModificacion = $datosJWT['idUsuario'];
        $DireccionEquipoModificacion = $IpCliente; // Cambiar por la dirección correcta
        $FechaModificacion = date('Y-m-d H:i:s');  // Genera la fecha actual

        $fechaInicialDateTime = DateTime::createFromFormat('d/m/Y', $FechaInicial);
        if ($fechaInicialDateTime !== false) {
            // Convierte la fecha al formato Y-m-d para guardarla en la base de datos
            $FechaInicial = $fechaInicialDateTime->format('Y-m-d');
        } else {
            die("Error: Formato de Fecha de Finalización inválido.");
        }

        // Verifica y convierte la fecha de finalización
        $fechaFinalizacionDateTime = DateTime::createFromFormat('d/m/Y', $FechaFinalizacion);
        if ($fechaFinalizacionDateTime !== false) {
            // Convierte la fecha al formato Y-m-d para guardarla en la base de datos
            $FechaFinalizacion = $fechaFinalizacionDateTime->format('Y-m-d');
        } else {
            die("Error: Formato de Fecha de Finalización inválido.");
        }

        // Prepara la consulta para prevenir inyección SQL
        $stmt = $db->prepare("CALL ActualizarInventario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Vincula los parámetros
        $stmt->bind_param(
            'iisiiissssiiisis',
            $IdInventario,
            $IdMaestroFormato,
            $IdArticulo,
            $IdCatalogoClasificacion,
            $IdCatalogoIdentificador,
            $IdCatalogoHistorial,
            $ResponsableActivo,
            $UbicacionActual,
            $FechaInicial,
            $FechaFinalizacion,
            $RevisionInterno,
            $RevisionExterno,
            $IdEstadoRegistro,
            $FechaModificacion,
            $IdUsuarioModificacion,
            $DireccionEquipoModificacion,
        );
        // Ejecuta la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public static function eliminar($idMaestroFormato)
    {
        $db = new Conectar();
        $datosJWT = Seguridad::getDataJwt();
        $IpCliente = Seguridad::obtenerIpCliente();
        $FechaModificacion = date('Y-m-d H:i:s');
        $IdUsuarioModificacion = $datosJWT['idUsuario'];
        $stmt = $db->prepare("CALL EliminarInventario(?,?,?)");
        $stmt->bind_param("isi", $idMaestroFormato, $FechaModificacion, $IdUsuarioModificacion);
        if ($stmt->execute()) {
            return TRUE;
        } //end if
        return FALSE;
    } //end delete

}//end class Cliente