<?php
    include './models/Mesa.php';
    include './models/DAO/MesaDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class MesaController {

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['idMozo']) && isset($body['estado'])) {
                $mesa = new Mesa($body['idMozo'], $body['estado']);
    
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
        
        public function mesaMasUsada(Request $request, Response $response, array $args) {
            $mesaMayor = 0;
            $id = 0;
            $listaMesas= MesaDAO::listar();

            foreach($listaMesas as $mesa) {
                if($mesa['cantClientes'] > $mesaMayor) {
                    $id = $mesa['id'];
                    $mesaMayor = $mesa['cantClientes'];
                }
            }

            $response->getBody()->write(json_encode(MesaDAO::listarUno($id)));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function cerrarMesa(Request $request, Response $response, array $args) {
            $id = $args['id'];

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);

                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);
    
                $mesa = MesaDAO::listarUno($id);
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'La mesa ya esta cerrada']));
                if($mesa['estado'] != "Cerrado") {
                    $mozoObj->cerrarMesa($id);
                    $response->getBody()->write(json_encode($mozoObj));
                }
            } else {
                $response->getBody()->write(json_encode(['estado' => 'Incorrecto', 'mensaje' => 'No inicio sesion']));
            }

            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarUno(Request $request, Response $response, array $args) {
            $mesa = MesaDAO::listarUno($args['id']);

            $response->getBody()->write(json_encode($mesa));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function modificar(Request $request, Response $response, array $args) {
            $id = $args['id'];
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            // if(isset($body['nombre']) && isset($body['precio']) && isset($body['tiempoMinutos'])) {
                $mesaId = MesaDAO::listarUno($id);
                $mesa = new Mesa($body['idMozo'], $body['estado']);
                $mesa->id = $id;
    
                if(MesaDAO::modificar($mesa, $mesaId['cantClientes'])) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Mesa modificado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al modificar el Mesa.'];
                }
            // }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function eliminar(Request $request, Response $response, array $args) {
            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al eliminar el Mesa.'];
            if(MesaDAO::eliminar($args['id'])) {
                $data = ['estado' => 'Correcto', 'mensaje' => 'Mesa eliminada con exito.'];
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>