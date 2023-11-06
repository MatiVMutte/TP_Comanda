<?php
    include './models/Mesa.php';
    include './models/DAO/MesaDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class MesaController {

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['idPedido']) && isset($body['idMozo']) && isset($body['estado'])) {
                $mesa = new Mesa($body['idPedido'], $body['idMozo'], $body['estado']);
    
                if(MesaDAO::insertar($mesa)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Mesa creado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al crear el mesa.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listar(Request $request, Response $response, array $args) {
            $listaMesas= MesaDAO::listar();

            $response->getBody()->write(json_encode($listaMesas));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarUno(Request $request, Response $response, array $args) {
            $mesa = MesaDAO::listarUno($args['id']);

            $response->getBody()->write(json_encode($mesa));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>