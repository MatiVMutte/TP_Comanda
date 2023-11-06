<?php

    class EmpleadoDAO {
        private static $conexion;

        public static function insertar(Empleado $empleado) {
            self::$conexion = DataBase::getConexion();

            $valorRol = $empleado->rol->value;
            $valorEstado = $empleado->estado->value;

            $query = 'INSERT INTO empleado SET rol=:rol, nombre=:nombre, disponible=:disponible, estado=:estado';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":rol", $valorRol);
            $consulta->bindParam(":nombre", $empleado->nombre);
            $consulta->bindParam(":disponible", $empleado->disponible);
            $consulta->bindParam(":estado", $valorEstado);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function listar() {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM empleado';
            $consulta = self::$conexion->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarUno($id) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM empleado WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_ASSOC);
        }
    }

?>