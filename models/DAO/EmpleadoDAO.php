<?php

    class EmpleadoDAO {
        private static $conexion;

        public static function insertar(Empleado $empleado) {
            self::$conexion = DataBase::getConexion();

            $valorRol = $empleado->rol->value;
            $valorEstado = $empleado->estado->value;

            $query = 'INSERT INTO empleado (rol, nombre, contrasenia, disponible, estado) VALUES (:rol, :nombre, :contrasenia, :disponible, :estado)';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":rol", $valorRol);
            $consulta->bindParam(":nombre", $empleado->nombre);
            $consulta->bindParam(":contrasenia", $empleado->contrasenia);
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

        public static function traerPorUsuarioYContra($usuario, $contrasenia) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM empleado WHERE nombre = :nombre AND contrasenia = :contrasenia';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":nombre", $usuario);
            $consulta->bindParam(":contrasenia", $contrasenia);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_ASSOC);
        }

        public static function modificar(Empleado $empleado) {
            self::$conexion = DataBase::getConexion();

            $valorEstado = $empleado->estado->value;
            $valorRol = $empleado->rol->value;

            $query = 'UPDATE empleado
                      SET nombre = :nombre,
                          contrasenia = :contrasenia,
                          disponible = :disponible,
                          estado = :estado,
                          rol = :rol
                      WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $empleado->id);
            $consulta->bindParam(":nombre", $empleado->nombre);
            $consulta->bindValue(":contrasenia", $empleado->contrasenia);
            $consulta->bindValue(":disponible", $empleado->disponible);
            $consulta->bindValue(":estado", $valorEstado);
            $consulta->bindValue(":rol", $valorRol);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }



        public static function eliminar($id) {
            self::$conexion = DataBase::getConexion();

            $query = 'DELETE FROM empleado
                      WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }
    }

?>