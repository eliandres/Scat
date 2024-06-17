<?php

    class Connection extends Mysqli {
        private static $host = "localhost";
        private static $user = "root";
        private static $password = "pa55w0rd.";
        private static $database = "scan";
        function __construct() {
            parent::__construct("localhost", 'root', 'pa55w0rd.', 'scan');
            $this->set_charset('utf8');
            $this->connect_error == NULL ? 'Conexión exítosa a la DB' : die('Error al conectarse a la BD');
        }//end __construct
    }//end class Connection