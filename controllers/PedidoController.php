<?php
    include './models/Pedido.php';
    include './models/DAO/PedidoDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class PedidoController {

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['idMesa']) && isset($body['nombreCliente']) && isset($body['totalPrecio']) && isset($body['estado']) && isset($body['tiempoEstimado'])) {
                $pedido = new Pedido($body['idMesa'], $body['nombreCliente'], $body['totalPrecio'], $body['estado'], $body['tiempoEstimado']);
    
                if(PedidoDAO::insertar($pedido)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Pedido creado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al crear el pedido.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listar(Request $request, Response $response, array $args) {
            $listaPedidos = PedidoDAO::listar();

            $response->getBody()->write(json_encode($listaPedidos));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarUno(Request $request, Response $response, array $args) {
            $pedido = PedidoDAO::listarUno($args['id']);

            $response->getBody()->write(json_encode($pedido));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function modificar(Request $request, Response $response, array $args) {
            $id = $args['id'];
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['idMesa']) && isset($body['nombreCliente']) && isset($body['totalPrecio']) && isset($body['estado']) && isset($body['tiempoEstimado'])) {
                $pedido = new Pedido($body['idMesa'], $body['nombreCliente'], $body['totalPrecio'], $body['estado'], $body['tiempoEstimado']);
                $pedido->id = $id;
    
                if(PedidoDAO::modificar($pedido)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Pedido modificado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al modificar el Pedido.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function eliminar(Request $request, Response $response, array $args) {
            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al eliminar el pedido.'];
            if(PedidoDAO::eliminar($args['id'])) {
                $data = ['estado' => 'Correcto', 'mensaje' => 'Pedido eliminado con exito.'];
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>
