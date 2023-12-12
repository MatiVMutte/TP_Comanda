<?php

use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use Slim\Factory\AppFactory;

include_once 'controllers/ProductoController.php';
include_once 'controllers/PedidoController.php';
include_once 'controllers/EmpleadoController.php';
include_once 'controllers/MesaController.php';
include_once 'controllers/MozoController.php';
include_once 'controllers/LogInController.php';
include_once 'controllers/ClienteController.php';
include_once 'middlewares/AutorizacionMiddleware.php';

require_once __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/TP_PHP"); // POR XAMPP - La raiz es HTDOC

$app->addBodyParsingMiddleware();

// Grupos de rutas que usan la función genérica con los campos correspondientes.
$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/', 'ProductoController:insertar');
    $group->get('[/]', 'ProductoController:listar');
    $group->get('/{id}', 'ProductoController:listarUno');
    $group->put('/{id}', 'ProductoController:modificar');
    $group->delete('/{id}', 'ProductoController:eliminar');
})->add(new AutorizacionMiddleware("Socio"));

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/', 'PedidoController:insertar');
    $group->get('[/]', 'PedidoController:listar');
    $group->get('/{id}', 'PedidoController:listarUno');
    $group->put('/{id}', 'PedidoController:modificar');
    $group->delete('/{id}', 'PedidoController:eliminar');
})->add(new AutorizacionMiddleware("Mozo"));

$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->post('/', 'EmpleadoController:insertar');
    $group->get('[/]', 'EmpleadoController:listar');
    $group->get('/{id}', 'EmpleadoController:listarUno');
    $group->put('/{id}', 'EmpleadoController:modificar');
    $group->delete('/{id}', 'EmpleadoController:eliminar');
})->add(new AutorizacionMiddleware("Socio"));

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/', 'MesaController:insertar');
    $group->get('[/]', 'MesaController:listar');
    $group->get('/{id}', 'MesaController:listarUno');
    $group->put('/{id}', 'MesaController:modificar');
    $group->delete('/{id}', 'MesaController:eliminar');
    $group->get('/masUsadas/', 'MesaController:mesaMasUsada');
    $group->post('/{id}', 'MesaController:cerrarMesa');
})->add(new AutorizacionMiddleware("Socio"));

// $app->group('/encuestas', function (RouteCollectorProxy $group) {
//     $group->post('/', 'EncuestaController:insertar');
//     $group->get('[/]', 'EncuestaController:listar');
//     $group->get('/{id}', 'EncuestaController:listarUno');
//     $group->delete('/{id}', 'EncuestaController:eliminar');
// });


$app->group('/mozo', function (RouteCollectorProxy $group) {
    $group->post('/', 'MozoController:abrirMesa');
    $group->post('/pedido[/]', 'MozoController:crearPedido');
    $group->post('/pedido/producto/agregar[/]', 'MozoController:insertarProductoAlPedido');
    $group->post('/pedido/producto/eliminar[/]', 'MozoController:eliminarProductoDelPedido');
})->add(new AutorizacionMiddleware("Mozo"));

$app->group('/productospedidos', function (RouteCollectorProxy $group) {
    $group->get('/enproceso[/]', 'EmpleadoController:listarEnProcesoPorTipo');
    $group->get('[/]', 'EmpleadoController:listarPendientesPorTipo');
    $group->put('/estadoenproceso[/]', 'EmpleadoController:cambiarEstadoAEnProceso');
});

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('/{id}', 'ClienteController:verInfoPedido');
});




$app->get('[/]', function (Request $request, Response $response, array $args) {
    echo "<h1>Bienvenido al HOME</h1>";
    return $response;
});

$app->post('/login[/]', 'LoginControlador:VerificarExistenciaUsuario');


$app->run();
?>
