<?php
    include './models/Empleado.php';
    include './models/DAO/EmpleadoDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class EmpleadoController {

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['rol']) && isset($body['nombre']) && isset($body['disponible']) && isset($body['estado'])) {
                $empleado = new Empleado($body['rol'], $body['nombre'], $body['disponible'], $body['estado']);
    
                if(EmpleadoDAO::insertar($empleado)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Empleado creado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al crear el emplaedo.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listar(Request $request, Response $response, array $args) {
            $listaProductos = EmpleadoDAO::listar();

            $response->getBody()->write(json_encode($listaProductos));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarUno(Request $request, Response $response, array $args) {
            $producto = EmpleadoDAO::listarUno($args['id']);

            $response->getBody()->write(json_encode($producto));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>