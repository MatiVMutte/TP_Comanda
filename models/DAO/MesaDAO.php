<?php

    class MesaDao {
        private static $conexion;

        public static function insertar(Mesa $mesa) {
            self::$conexion = DataBase::getConexion();


            $valor = $mesa->estado->value;

            $query = 'INSERT INTO mesa SET idPedido=:idPedido, idMozo=:idMozo, estado=:estado';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":idPedido", $mesa->idPedido);
            $consulta->bindParam(":idMozo", $mesa->idMozo);
            $consulta->bindParam(":estado", $valor);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function listar() {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM mesa';
            $consulta = self::$conexion->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarUno($id) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM mesa WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_ASSOC);
        }
    }

?>