<?php

namespace App\config;
// Incluir la biblioteca JWT
require_once 'libs/php-jwt/src/JWT.php';
require_once 'libs/php-jwt/src/Key.php';
require_once 'libs/php-jwt/src/SignatureInvalidException.php';
require_once 'config/responseHttp.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use App\config\ResponseHttp;

class Seguridad
{
    private static $jwt_data; // Propiedad para guardar los datos decodificados del JWT 

    final public static function secretaKey()
    {
        $key = getenv("SECRETAJWT");
        if (!$key) {
            throw new \Exception('Error al cargar la clave secreta');
        }
        return $key;
    }

    /*********************Crea el Token********************/
    final public static function createTokenJwt(array $data)
    {
        $key = self::secretaKey();
        $alg = 'HS256';
        $payload = [
            "iat" => time(),
            "exp" => time() + (60 * 60 * 6),
            "data" => $data
        ];
        return JWT::encode($payload, $key, $alg);
    }

    /*********************Validar que el JWT sea correcto********************/
    final public static function validaTokenJwt()
    {
        try {
            if (!isset(getallheaders()['Authorization'])) {
                echo json_encode(ResponseHttp::status401());
                exit;
            }

            $jwt = explode(" ", getallheaders()['Authorization']);
            $token = $jwt[1]; // Token JWT a decodificar
            ;
            $key = self::secretaKey();

            $data = JWT::decode($token, new Key($key, 'HS256'));

            // Guardar los datos decodificados en una propiedad estática
            self::$jwt_data = $data;
            return $data;
        } catch (SignatureInvalidException $e) {
            echo json_encode(ResponseHttp::status401());
            exit;
        } catch (\Exception $e) {
            error_log($e);
            echo json_encode(ResponseHttp::status400());
            exit;
        }
    }

    /***************Devolver los datos del JWT decodificados****************/
    final public static function getDataJwt()
    {
        if (!self::$jwt_data) {
            throw new \Exception('No hay datos JWT disponibles');
        }

        $jwt_decoded_array = json_decode(json_encode(self::$jwt_data), true);
        return $jwt_decoded_array['data'];
    }

    /********Encriptar la contraseña del usuario***********/
    final public static function createPassword(string $pw)
    {
        $pass = password_hash($pw, PASSWORD_DEFAULT);
        return $pass;
    }

    /*****************Validar que las contraseñas coincidan****************/
    final public static function validateContrasena(string $pw, string $pwh)
    {
        if (password_verify($pw, $pwh)) {
            return true;
        } else {
            error_log('La contraseña es incorrecta');
            return false;
        }
    }

    /*****************Validar La IP del cliente****************/
    final public static function obtenerIpCliente()
    {
        $ip = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
