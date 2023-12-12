<?php
    include_once './models/Empleado.php';
    include_once './models/DAO/EmpleadoDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class ClienteController {

        public function verInfoPedido(Request $request, Response $response, array $args) {
            $id = $args['id'];

            $pedido = PedidoDAO::listarUno($id);

            if($pedido != false) {
                $response->getBody()->write(json_encode(['estado' => 'Correcto', 'mensaje' => 'El tiempo de demora de tu pedido es de ' . ProductoPedidoDAO::obtenerDemoraPedido() . ' minutos']));
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No encontro el pedido']));
            }
            return $response->withHeader('Content-Type', 'application/json');
        }

        
    }

?>
