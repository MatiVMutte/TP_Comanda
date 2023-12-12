<?php
    include './models/Empleado.php';
    include './models/DAO/EmpleadoDAO.php';
    include_once './models/ProductoPedidoEnum.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class EmpleadoController {

        public function listarPendientesPorTipo(Request $request, Response $response, array $args) {
            $productosDelRol = [];
            $productosPendientes = [];
            $productosPedidos = ProductoPedidoDAO::listar();
            $productos = ProductoDAO::listar();

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);


                foreach($productos as $producto) {
                    if($producto['tipoProducto'] == $mozoObj->rol->value) {
                        $productosDelRol[] = $producto;
                    }
                }

                foreach($productosPedidos as $productoPedido) {
                    for($i = 0; $i < count($productosDelRol); $i++) {
                        if($productoPedido['idProducto'] == $productosDelRol[$i]['id'] && $productoPedido['estado'] == "Pendiente") {
                            $productosPendientes[] = $productoPedido;
                        }
                    }
                }

                if(empty($productosPendientes)) {
                    $productosPendientes = "No tiene productos pendientes para su tipo de empleado";
                }

            }


            $response->getBody()->write(json_encode($productosPendientes));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarEnProcesoPorTipo(Request $request, Response $response, array $args) {
            $productosDelRol = [];
            $productosEnProceso = [];
            $productosPedidos = ProductoPedidoDAO::listar();
            $productos = ProductoDAO::listar();

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);


                foreach($productos as $producto) {
                    if($producto['tipoProducto'] == $mozoObj->rol->value) {
                        $productosDelRol[] = $producto;
                    }
                }

                foreach($productosPedidos as $productoPedido) {
                    for($i = 0; $i < count($productosDelRol); $i++) {
                        if($productoPedido['idProducto'] == $productosDelRol[$i]['id'] && $productoPedido['estado'] == "EnProceso") {
                            $productosEnProceso[] = $productoPedido;
                        }
                    }
                }

                if(empty($productosEnProceso)) {
                    $productosEnProceso = "No tiene productos en proceso para su tipo de empleado";
                }

            }


            $response->getBody()->write(json_encode($productosEnProceso));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function cambiarEstadoAEnProceso(Request $request, Response $response, array $args) {
            $flag = 0;
            $respuesta = "Error al poner el producto en proceso";
            $body = (array)$request->getParsedBody();
            $productoPedido = ProductoPedidoDAO::listarUno($body['idProducto']);
            
            // foreach($productosPedidos as $producto) {
            if($body['idProducto'] == $productoPedido['id'] && $productoPedido['estado'] == "Pendiente") {
                $productoPedido['estado'] = EstadoProducto::EnProceso;
                var_dump($productoPedido['estado']);
                ProductoPedidoDAO::modificar($productoPedido['idProducto'], $productoPedido['idPedido'], $productoPedido['estado'], $productoPedido['demora']);
                $flag = 1;
            }
            // }

            session_start();
            if(isset($_SESSION['idEmpleado'])) {
                $mozo = EmpleadoDAO::listarUno($_SESSION['idEmpleado']);
                $mozoObj = new Empleado($mozo['rol'], $mozo['nombre'], $mozo['contrasenia'], $mozo['disponible'], $mozo['estado'], $mozo['id']);

                
            }

            if($flag == 1) {
                $respuesta = "Producto marcado --> en proceso";
            }

            $response->getBody()->write(json_encode($respuesta));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            // if(isset($body['rol']) && isset($body['nombre']) && isset($body['disponible']) && isset($body['estado'])) {
                $empleado = new Empleado($body['rol'], $body['nombre'], $body['contrasenia'], $body['disponible'], $body['estado']);
    
                if(EmpleadoDAO::insertar($empleado)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Empleado creado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al crear el emplaedo.'];
                }
            // }

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

        public function modificar(Request $request, Response $response, array $args) {
            $id = $args['id'];
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            // if(isset($body['idMesa']) && isset($body['nombreCliente']) && isset($body['totalPrecio']) && isset($body['estado']) && isset($body['tiempoEstimado'])) {
                $empleado = new Empleado($body['rol'], $body['nombre'], $body['contrasenia'], $body['disponible'], $body['estado']);
                $empleado->id = $id;
    
                if(EmpleadoDAO::modificar($empleado)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Pedido modificado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al modificar el Pedido.'];
                }
            // }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function eliminar(Request $request, Response $response, array $args) {
            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al eliminar el empleado.'];
            if(EmpleadoDAO::eliminar($args['id'])) {
                $data = ['estado' => 'Correcto', 'mensaje' => 'Empleado eliminado con exito.'];
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>
