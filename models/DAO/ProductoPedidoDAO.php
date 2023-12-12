<?php

    include_once './db/Database.php';

    class ProductoPedidoDAO {
        private static $conexion;

        public static function obtenerDemoraPedido() {
            self::$conexion = DataBase::getConexion();

            $query = 'SELECT SUM(demora) AS suma_total FROM productopedido';
            $consulta = self::$conexion->prepare($query);
            if($consulta->execute()) {
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                return $resultado['suma_total'];
            }
            return false;
        }

        public static function insertar($idProducto, $idPedido, $estado) {
            self::$conexion = DataBase::getConexion();

            $query = 'INSERT INTO productopedido SET idProducto=:idProducto, estado=:estado, idPedido=:idPedido';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":idProducto", $idProducto);
            $consulta->bindParam(":idPedido", $idPedido);
            $consulta->bindParam(":estado", $estado);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function listar() {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM productopedido';
            $consulta = self::$conexion->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarUno($id) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM productopedido WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            if($consulta->execute()) {
                return $consulta->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }

        public static function listarProductosXPedido($idPedido) {
            $productos = [];
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM productopedido WHERE $idPedido = :$idPedido';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":$idPedido", $idPedido);
            if($consulta->execute()) {
                $productosIds = $consulta->fetch(PDO::FETCH_ASSOC);
                foreach($productosIds as $productoId) {
                    $productos[] = ProductoDAO::listarUno($productoId['idProducto']);
                }
            }
            return $productos;
        }

        public static function modificar($idProducto, $idPedido, $estado, $demora) {
            self::$conexion = DataBase::getConexion();

            $estadoProducto = $estado->value;

            $query = 'UPDATE productopedido
                      SET idProducto=:idProducto, estado=:estado, idPedido=:idPedido, demora=:demora
                      WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":idProducto", $idProducto);
            $consulta->bindParam(":idPedido", $idPedido);
            $consulta->bindParam(":estado", $estadoProducto);
            $consulta->bindParam(":demora", $demora);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function eliminar($id) {
            self::$conexion = DataBase::getConexion();

            $query = 'DELETE FROM productopedido
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