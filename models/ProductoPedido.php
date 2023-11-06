<?php

    enum EstadoProducto {
        case Realizado;
        case Pendiente;
        case EnProceso;
    }

    class ProductoPedido {
        private int $id;
        private int $idProducto;
        private int $idPedido;
        private EstadoProducto $estado;

        public function __construct(int $idProducto, EstadoProducto $estado) {
            $this->idProducto = $idProducto;
            $this->estado = $estado;
        }
    }

?>