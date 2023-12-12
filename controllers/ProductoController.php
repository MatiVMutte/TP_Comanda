<?php
    include './models/Producto.php';
    include './models/DAO/ProductoDAO.php';
    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    class ProductoController {

        public function insertar(Request $request, Response $response, array $args) {
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['nombre']) && isset($body['precio']) && isset($body['tiempoMinutos'])) {
                $producto = new Producto($body['nombre'], $body['precio'], $body['tipoProducto'], $body['tiempoMinutos']);
    
                if(ProductoDAO::insertar($producto)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Producto creado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al crear el producto.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listar(Request $request, Response $response, array $args) {
            $listaProductos = ProductoDAO::listar();

            $response->getBody()->write(json_encode($listaProductos));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function listarUno(Request $request, Response $response, array $args) {
            $producto = ProductoDAO::listarUno($args['id']);

            $response->getBody()->write(json_encode($producto));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function modificar(Request $request, Response $response, array $args) {
            $id = $args['id'];
            $body = (array)$request->getParsedBody();

            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Datos o alguno de los datos fueron vacios.'];
            if(isset($body['nombre']) && isset($body['precio']) && isset($body['tiempoMinutos'])) {
                $producto = new Producto($body['nombre'], $body['precio'], $body['tipoProducto'], $body['tiempoMinutos']);
                $producto->id = $id;
    
                if(ProductoDAO::modificar($producto)) {
                    $data = ['estado' => 'Correcto', 'mensaje' => 'Producto modificado con exito.'];
                } else {
                    $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al modificar el producto.'];
                }
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }

        public function eliminar(Request $request, Response $response, array $args) {
            $data = ['estado' => 'Incorrecto', 'mensaje' => 'Hubo un error al eliminar el producto.'];
            if(ProductoDAO::eliminar($args['id'])) {
                $data = ['estado' => 'Correcto', 'mensaje' => 'Producto eliminado con exito.'];
            }

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }

?>