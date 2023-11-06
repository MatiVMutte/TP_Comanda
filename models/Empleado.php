<?php

    enum Rol : string {
        case Bartender = 'Bartender';
        case Cervecero = 'Cervecero';
        case Cocinero = 'Cocinero';
        case Mozo = 'Mozo';
    }

    enum EstadoEmpleado : string {
        case Presente = 'Presente';
        case Ausente = 'Ausente';
    }

    class Empleado {
        public int $id;
        public Rol $rol;
        public string $nombre;
        public bool $disponible;
        public EstadoEmpleado $estado; // Presente - Ausente

        public function __construct($rol, string $nombre, bool $disponible, $estado, int $id=-1) {
            $this->rol = Rol::from($rol);
            $this->nombre = $nombre;
            $this->disponible = $disponible;
            $this->estado = EstadoEmpleado::from($estado);
            $this->id = $id;
        }
    }
?>