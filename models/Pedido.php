<?php

    enum EstadoPedido : string {
        case Entregado = 'Entregado';
        case EnPreparacion = 'EnPreparacion';
        case Cancelado = 'Cancelado';
    }

    class Pedido {
        public string $id;
        public array $idsProductosPedidos = array();
        public int $idMesa;
        public string $nombreCliente;
        public float $totalPrecio;
        public EstadoPedido $estado;
        public $tiempoEstimado;

        public function __construct(int $idMesa, string $nombreCliente, float $totalPrecio, $estado="EnPreparacion", $tiempoEstimado=-1, int $id=-1, array $idsProductosPedidos=array()) {
            $this->idsProductosPedidos = $idsProductosPedidos;
            $this->idMesa = $idMesa;
            $this->nombreCliente = $nombreCliente;
            $this->totalPrecio = $totalPrecio;
            $this->estado = EstadoPedido::from($estado);
            $this->tiempoEstimado = $tiempoEstimado;
            $this->id = $id;
        }

        public static function generarIDAlfaNumerico() {
            $id = '';
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            for($i = 0; $i < 5; $i++) {
                $id .= $caracteres[rand(0, strlen($caracteres)-1)];
            }
            return $id;
        }
    }

?>