<?php
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

include 'controllers/ProductoController.php';
include 'controllers/PedidoController.php';
include 'controllers/EmpleadoController.php';
include 'controllers/MesaController.php';

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/TP_PHP"); // POR XAMPP - La raiz es HTDOC

/**
 * Middleware que se EJECUTA ANTES de la solicitud PRINCIPAL. 
 *          Este toma la solicitud y el manejador, maneja la solicitud, 
 *          luego agrega "BEFORE" al contenido existe de la respuesta.
 * 
 */
// $beforeMiddleware = function (Request $request, RequestHandler $handler) { 
//     $response = $handler->handle($request);
//     $existingContent = (string) $response->getBody();

//     $response = new Response();
//     $response->getBody()->write('BEFORE' . $existingContent);

//     return $response;
// };

/**
 * Middleware que se EJECUTA DESPUES de la solicitud PRINCIPAL. 
 *          Este middleware toma la solicitud y el manejador, maneja la solicitud, 
 *          y luego agrega ‘AFTER’ al final de la respuesta.
 */
// $afterMiddleware = function ($request, $handler) {
//     $response = $handler->handle($request);
//     $response->getBody()->write('AFTER');
//     return $response;
// };


/**
 * Agrega estos MIDDLEWARE a la aplicacion
 */
// $app->add($beforeMiddleware);
// $app->add($afterMiddleware);

/**
 * EJEMPLO DE USO DE MIDDLEWARE
 */

 // <><><><><><><> EJEMPLO DE MIDDLEWARE <><><><><><><>

// $app->add(function (Request $request, RequestHandler $handler) {
//     $response = $handler->handle($request);
//     $existingContent = (string) $response->getBody();

//     $response = new \Slim\Psr7\Response();
//     $response->getBody()->write('BEFORE ' . $existingContent);

//     return $response;
// });

// $app->add(function (Request $request, RequestHandler $handler) {
//     $response = $handler->handle($request);
//     $response->getBody()->write(' AFTER');
//     return $response;
// });

// $app->get('[/]', function (Request $request, Response $response, array $args) {
//     $response->getBody()->write('Hello World');
//     return $response;
// });


// Función genérica que comprueba si los campos necesarios están presentes.
function verificarCampos($campos, $request, $handler) {
    $data = $request->getParsedBody();

    // Comprueba si todos los campos necesarios están presentes.
    foreach ($campos as $campo) {
        if (!isset($data[$campo])) {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Falta el campo ' . $campo . '.']));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
        
    $response = $handler->handle($request);
    return $response;
}

// Arreglos de campos necesarios para cada entidad.
$camposEmpleado = ['nombre', 'disponible', 'estado', 'rol'];
$camposProducto = ['nombre', 'precio', 'tipoProducto', 'tiempoMinutos'];
$camposPedido = ['idMesa', 'nombreCliente', 'totalPrecio', 'estado', 'tiempoEstimado'];
$camposMesa = ['idPedido', 'idMozo', 'estado'];

// Grupos de rutas que usan la función genérica con los campos correspondientes.
$app->group('/productos', function (RouteCollectorProxy $group) use ($camposProducto) {
    $group->post('/', 'ProductoController:insertar')->add(function($request, $handler) use ($camposProducto) {
        return verificarCampos($camposProducto, $request, $handler);
    });
    $group->get('[/]', 'ProductoController:listar');
    $group->get('/{id}', 'ProductoController:listarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) use ($camposPedido) {
    $group->post('/', 'PedidoController:insertar')->add(function($request, $handler) use ($camposPedido) {
        return verificarCampos($camposPedido, $request, $handler);
    });
    $group->get('[/]', 'PedidoController:listar');
    $group->get('/{id}', 'PedidoController:listarUno');
});

$app->group('/empleados', function (RouteCollectorProxy $group) use ($camposEmpleado) {
    $group->post('/', 'EmpleadoController:insertar')->add(function($request, $handler) use ($camposEmpleado) {
        return verificarCampos($camposEmpleado, $request, $handler);
    });
    $group->get('[/]', 'EmpleadoController:listar');
    $group->get('/{id}', 'EmpleadoController:listarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group) use ($camposMesa) {
    $group->post('/', 'MesaController:insertar')->add(function($request, $handler) use ($camposMesa) {
        return verificarCampos($camposMesa, $request, $handler);
    });
    $group->get('[/]', 'MesaController:listar');
    $group->get('/{id}', 'MesaController:listarUno');
});






$app->get('[/]', function (Request $request, Response $response, array $args) {
    echo "<h1>Bienvenido al HOME</h1>";
    return $response;
});


$app->run();
?>