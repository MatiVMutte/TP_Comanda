<?php



    class PedidoDAO {
        private static $conexion;

        public static function insertar(Pedido $pedido) {
            self::$conexion = DataBase::getConexion();

            $valor = $pedido->estado->value;

            $pedido->id = Pedido::generarIDAlfaNumerico();
            $query = 'INSERT INTO pedido SET id=:id, idMesa=:idMesa, nombreCliente=:nombreCliente, totalPrecio=:totalPrecio, estado=:estado, tiempoEstimado=:tiempoEstimado';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $pedido->id);
            $consulta->bindValue(":idMesa", $pedido->idMesa);
            $consulta->bindParam(":nombreCliente", $pedido->nombreCliente);
            $consulta->bindParam(":totalPrecio", $pedido->totalPrecio);
            $consulta->bindParam(":estado", $valor);
            $consulta->bindParam(":tiempoEstimado", $pedido->tiempoEstimado);
            if($consulta->execute()) {
                return true;
            }
            return false;
        }

        public static function listar() {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM pedido';
            $consulta = self::$conexion->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function listarUno($id) {
            self::$conexion = Database::getConexion();

            $query = 'SELECT * FROM pedido WHERE id = :id';
            $consulta = self::$conexion->prepare($query);
            $consulta->bindParam(":id", $id);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_ASSOC);
        }
    }

?>