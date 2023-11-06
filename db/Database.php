<?php

    class Database {
        private static $host = "localhost";
        private static $db_nombre = "comanda";
        private static $usuario = "root";
        private static $contraseña = "";
        private static $conexion;

        public static function getConexion() {
            self::$conexion = null;
            try {
                self::$conexion = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_nombre, self::$usuario, self::$contraseña);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Conexion error: " . $e->getMessage();
            }
            return self::$conexion;
        }

        public function getUltimoID() {
            return self::$conexion->lastInsertId();
        }
    }

?>