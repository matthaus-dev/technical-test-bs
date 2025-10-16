<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use App\services\ShoppingCartService;

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

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

$app->get('/products', function (Request $request, Response $response) {
    $products = ShoppingCartService::getAvailableProducts();
    $payload = json_encode(['products' => $products], JSON_UNESCAPED_UNICODE);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/cart/total', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    $result = ShoppingCartService::getTotalCartStatic(is_array($body) ? $body : []);
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
