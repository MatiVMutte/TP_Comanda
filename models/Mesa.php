<?php

    enum Estado : string {
        case Esperando = 'Esperando';
        case Comiendo = 'Comiendo';
        case Pagando = 'Pagando';
        case Cerrado = 'Cerrado';
    }

    class Mesa {
        public int $id;
        public ?int $idMozo;
        public Estado $estado;

        public function __construct($idMozo = -1, $estado="Cerrado", $id=-1) {
            $this->idMozo = $idMozo;
            $this->id = $id;
            $this->estado = Estado::from($estado);
        }
    }
    

?>