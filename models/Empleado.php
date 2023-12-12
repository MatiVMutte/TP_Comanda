<?php

include_once "./models/DAO/ProductoPedidoDAO.php";

    enum TipoRol : string {
        case Bartender = 'Bartender';
        case Cervecero = 'Cervecero';
        case Cocinero = 'Cocinero';
        case Mozo = 'Mozo';
        case Socio = 'Socio';
    }

    enum EstadoEmpleado : string {
        case Presente = 'Presente';
        case Ausente = 'Ausente';
    }

    class Empleado {
        public int $id;
        public TipoRol $rol;
        public string $nombre;
        public string $contrasenia;
        public bool $disponible;
        public EstadoEmpleado $estado; // Presente - Ausente

        public function __construct($rol, string $nombre, string $contrasenia, bool $disponible, $estado, int $id=-1) {
            $this->rol = TipoRol::from($rol);
            $this->nombre = $nombre;
            $this->contrasenia = $contrasenia;
            $this->disponible = $disponible;
            $this->estado = EstadoEmpleado::from($estado);
            $this->id = $id;
        }

        public function buscarMesaLibre() {
            $mesas = MesaDAO::listar();
            foreach ($mesas as $mesa) {
                if($mesa['estado'] == "Cerrado") {
                    var_dump($mesa);
                    return new Mesa($mesa['idMozo'], $mesa['estado'], $mesa['id']);
                }
            }
            return null;
        }

        public function abrirMesa($id) {
            echo "JOLA";
            $mesa = MesaDAO::listarUno($id);
            $mesa['idMozo'] = $this->id;
            $mesa['estado'] = "Esperando";
            MesaDAO::modificar(new Mesa($mesa['idMozo'], $mesa['estado'], $mesa['id']), $mesa['cantClientes']);
            return $mesa;
        }

        public function cerrarMesa($id) {
            $mesa = MesaDAO::listarUno($id);
            $mesa['idMozo'] = NULL;
            $mesa['estado'] = "Cerrado";
            $mesa['cantClientes']++;
            MesaDAO::modificar(new Mesa($mesa['idMozo'], $mesa['estado'], $mesa['id']), $mesa['cantClientes']);
        }

        public function crearPedido($idMesa, $nombreCliente) {
            $pedido = new Pedido($idMesa, $nombreCliente, 0);
            PedidoDAO::insertar($pedido);
            return $pedido;
        }

        public function agregarProductoAlPedido($idPedido, $idProducto) {
            ProductoPedidoDAO::insertar($idProducto, $idPedido, "Pendiente");
            $pedido = PedidoDAO::listarUno($idPedido);
            $producto = ProductoDAO::listarUno($idProducto);
            $pedido['totalPrecio'] += $producto['precio'];
            $pedido['tiempoEstimado'] += $producto['tiempoMinutos'];
            return PedidoDAO::modificar(new Pedido($pedido['idMesa'], $pedido['nombreCliente'], $pedido['totalPrecio'], $pedido['estado'], $pedido['tiempoEstimado'], $pedido['id']));
        }

        public function sacarProductoDelPedido($idPedido, $idProducto) {
            $productosPedidos = ProductoPedidoDAO::listar();
            foreach ($productosPedidos as $producto) {
                if($producto['idPedido'] == $idPedido && $producto['idProducto'] == $idProducto) {
                    $sePudo = ProductoPedidoDAO::eliminar($producto['id']);
                    if($sePudo) {
                        $pedido = PedidoDAO::listarUno($idPedido);
                        $producto = ProductoDAO::listarUno($idProducto);
                        $pedido['totalPrecio'] -= $producto['precio'];
                        $pedido['tiempoEstimado'] -= $producto['tiempoMinutos'];
                        echo "osiadjpoadj";
                        return PedidoDAO::modificar(new Pedido($pedido['idMesa'], $pedido['nombreCliente'], $pedido['totalPrecio'], $pedido['estado'], $pedido['tiempoEstimado'], $pedido['id']));
                    }
                    break;
                }
            }
            return false;
        }

        public function entregarPedidos() {
            $pedidos = PedidoDAO::listar();
            foreach($pedidos as $pedido) {
                if($pedido['estado'] == "Listo") {
                    $mesa = MesaDAO::listarUno($pedido['idMesa']);
                    $mesa['estado'] = "Listo";
                    MesaDAO::modificar(new Mesa($mesa['idMozo'], $mesa['estado'], $mesa['id']), $mesa['cantClientes']);
                }
            }
        }
    }
?>