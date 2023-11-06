<?php
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


$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->post('/', 'ProductoController:insertar');
    $group->get('[/]', 'ProductoController:listar');
    $group->get('/{id}', 'ProductoController:listarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->post('/', 'PedidoController:insertar');
    $group->get('[/]', 'PedidoController:listar');
    $group->get('/{id}', 'PedidoController:listarUno');
});

$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->post('/', 'EmpleadoController:insertar');
    $group->get('[/]', 'EmpleadoController:listar');
    $group->get('/{id}', 'EmpleadoController:listarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->post('/', 'MesaController:insertar');
    $group->get('[/]', 'MesaController:listar');
    $group->get('/{id}', 'MesaController:listarUno');
});



// $app->get('/', function (Request $request, Response $response, array $args) {
//     echo "hola mundo";
//     return $response;
// });




$app->run();
?>