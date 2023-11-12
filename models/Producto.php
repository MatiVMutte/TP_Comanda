<?php

    
    enum TipoProducto : string {
        case Bartender = 'Bartender';
        case Cervecero = 'Cervecero';
        case Cocinero = 'Cocinero';
    }

    class Producto {
        public int $id;
        public string $nombre;
        public float $precio;
        public TipoProducto $tipoProducto;
        public $tiempoMinutos;

        public function __construct(string $nombre, float $precio, $tipoProducto, $tiempoMinutos, int $id=-1) {
            $this->nombre = $nombre;
            $this->precio = $precio;
            $this->tipoProducto = TipoProducto::from($tipoProducto);
            $this->tiempoMinutos = $tiempoMinutos;
            $this->id = $id;
        }
    }

?>