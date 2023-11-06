<?php

    class Encuesta {
        public int $idMesa;
        public string $nombreCliente;
        public int $puntuacionMesa;
        public int $puntuacionRestaurante;
        public int $puntuacionMozo;
        public int $puntuacionCocinero;
        public string $descripcion;

        public function __construct(int $idMesa, string $nombreCliente, int $puntuacionMesa, int $puntuacionRestaurante, int $puntuacionMozo, int $puntuacionCocinero, string $descripcion) {
            $this->idMesa = $idMesa;
            $this->nombreCliente = $nombreCliente;
            $this->puntuacionMesa = $puntuacionMesa;
            $this->puntuacionRestaurante = $puntuacionRestaurante;
            $this->puntuacionMozo = $puntuacionMozo;
            $this->puntuacionCocinero = $puntuacionCocinero;
            $this->descripcion = $descripcion;
        }
    }

?>