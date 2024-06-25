<?php
require_once 'config/responseHttp.php';
require_once 'config/seguridad.php';
require_once "conexion/conectar.php";

use App\config\ResponseHttp;
use App\config\seguridad;

class autentica
{
    public static function login($usuario, $contraseña)
    {
        try {
            $db = new Conectar();
            $query = "SELECT * FROM usuario WHERE NombreUsuario='$usuario'";
            $resultado = $db->query($query);
            $datos = [];

            if ($resultado->num_rows == 0) {
                return ResponseHttp::status400('El usuario o contraseña son incorrectos');
            } else {
                while ($row = $resultado->fetch_assoc()) {
                    if (seguridad::validateContrasena($contraseña, $row['Contrasena'])) {
                        $nombreUsuario = [
                            'nombreUsuario' => $row['NombreUsuario'],
                            'idUsuario' => $row['IdUsuario']
                        ];
                        $token = seguridad::createTokenJwt($nombreUsuario);
                        $datos = [
                            'idUsuario' => $row['IdUsuario'],
                            'usuario' => $row['NombreUsuario'],
                            'telefono' => $row['Telefono'],
                            'token' => $token
                        ];
                    } else {
                        return ResponseHttp::status400('El usuario o contraseña son incorrectos');
                    }
                } //en          
            }
            return $datos;
        } catch (\PDOException $e) {
            error_log('error autentica -> ' . $e);
            die(json_encode(ResponseHttp::status500()));
        }
    }
}
