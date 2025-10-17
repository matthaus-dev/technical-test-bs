<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use App\services\ShoppingCartService;
use App\controllers\CartController;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

$displayErrorDetails = false;
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, true, false);

$app->add(function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});

$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
});


$shoppingCartService = new ShoppingCartService();

$cartController = new CartController($shoppingCartService);

$app->get('/products', [$cartController, 'listProducts']);
$app->post('/cart/total', [$cartController, 'calculateTotal']);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write('API is running');
    return $response;
});

$app->run();
