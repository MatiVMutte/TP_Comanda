<?php

    enum Estado : string {
        case Esperando = 'Esperando';
        case Comiendo = 'Comiendo';
        case Pagando = 'Pagando';
        case Cerrado = 'Cerrado';
    }

    class Mesa {
        public int $id;
        public int $idPedido;
        public int $idMozo;
        public Estado $estado;

        public function __construct(int $idPedido = -1, int $idMozo = -1, $estado="Cerrado", int $id=-1) {
            $this->idPedido = $idPedido;
            $this->idMozo = $idMozo;
            // ---- opcionales ----
            $this->id = $id;
            $this->estado = Estado::from($estado);
        }
    }
    

?>