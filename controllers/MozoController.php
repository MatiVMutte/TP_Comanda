<?php
    include_once './models/Empleado.php';
    include_once './models/DAO/EmpleadoDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class MozoController {

        public function abrirMesa(Request $request, Response $response, array $args) {

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);

                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);
    
                $mesa = $mozoObj->buscarMesaLibre();
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No se encontraron mesas libres']));
                if($mesa != null) {
                    $mozoObj->abrirMesa($mesa->id);
                    $response->getBody()->write(json_encode($mozoObj));
                }
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No inicio sesion']));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function crearPedido(Request $request, Response $response, array $args) {
            $hayPedidoEnLaMesa = false;
            $body = (array)$request->getParsedBody();

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);
    
                $pedidos = PedidoDAO::listar();
                foreach( $pedidos as $pedido ) {
                    if($pedido['idMesa'] == $body['idMesa']) {
                        $hayPedidoEnLaMesa = true;
                        break;
                    }
                }
                if(!$hayPedidoEnLaMesa) {
                    $pedido = $mozoObj->crearPedido($body['idMesa'], $body['nombreCliente']);
                    $response->getBody()->write(json_encode($pedido));
                } else {
                    $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'Ya tiene un pedido esa mesa']));
                }
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No inicio sesion']));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function insertarProductoAlPedido(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);
    
                if(ProductoDAO::listarUno($body['idProducto']) != null && PedidoDAO::listarUno($body['idPedido']) != null) {
                    $mozoObj->agregarProductoAlPedido($body['idPedido'], $body['idProducto']);
                    $response->getBody()->write(json_encode(['estado' => 'Correcto', 'mensaje' => 'El producto se agrego al pedido']));
                } else {
                    if(ProductoDAO::listarUno($body['idProducto']) == null) {
                        $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'El producto no existe']));
                    } else {
                        $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'El pedido no existe']));
                    }
                }
                
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No inicio sesion']));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function eliminarProductoDelPedido(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);
    
                if(ProductoDAO::listarUno($body['idProducto']) != null && PedidoDAO::listarUno($body['idPedido']) != null) {
                    $mozoObj->sacarProductoDelPedido($body['idPedido'], $body['idProducto']);
                    $response->getBody()->write(json_encode(['estado' => 'Correcto', 'mensaje' => 'El producto se quito del pedido']));
                } else {
                    if(ProductoDAO::listarUno($body['idProducto']) == null) {
                        $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'El producto no existe']));
                    } else {
                        $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'El pedido no existe']));
                    }
                }
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No inicio sesion']));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        
    }

?>
