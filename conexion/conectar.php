<?php

use App\config\Dotenv;
use App\config\ErrorLog;

require './config/env_loader.php';

class Conectar extends Mysqli
{
    private static $host;
    private static $user;
    private static $password;
    private static $database;

    function __construct()
    {
        self::$host = getenv('DB_HOST');
        self::$user = getenv('DB_USER');
        self::$password = getenv('DB_PASSWORD');
        self::$database = getenv('DB_DATABASE');

        parent::__construct(self::$host, self::$user, self::$password, self::$database);
        $this->set_charset('utf8');
        if ($this->connect_error) {
            error_log('Error de conexion :' . $this->connect_error);
        }
    }
}
