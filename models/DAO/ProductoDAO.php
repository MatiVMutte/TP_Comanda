<?php

    include_once './db/Database.php';

    class ProductoDAO {
        private static $conexion;

        public static function insertar(Producto $producto) {
            self::$conexion = DataBase::getConexion();

            $valor = $producto->tipoProducto->value;

            $query = 'INSERT INTO productos SET nombre=:nombre, precio=:precio, tipoProducto=:tipoProducto, tiempoMinutos=:tiempoMinutos';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":nombre", $producto->nombre);
            $consulta->bindValue(":precio", $producto->precio);
            $consulta->bindParam(":tipoProducto", $valor);
            $consulta->bindParam(":tiempoMinutos", $producto->tiempoMinutos);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function listar() {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM productos';
            $consulta = self::$conexion->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarUno($id) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM productos WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            if($consulta->execute()) {
                return $consulta->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }

        public static function modificar(Producto $producto) {
            self::$conexion = DataBase::getConexion();

            $valor = $producto->tipoProducto->value;

            $query = 'UPDATE productos
                      SET nombre = :nombre,
                          precio = :precio,
                          tipoProducto = :tipoProducto,
                          tiempoMinutos = :tiempoMinutos
                      WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $producto->id);
            $consulta->bindParam(":nombre", $producto->nombre);
            $consulta->bindValue(":precio", $producto->precio);
            $consulta->bindParam(":tipoProducto", $valor);
            $consulta->bindParam(":tiempoMinutos", $producto->tiempoMinutos);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function eliminar($id) {
            self::$conexion = DataBase::getConexion();

            $query = 'DELETE FROM productos
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