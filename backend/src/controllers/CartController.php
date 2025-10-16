<?php

namespace App\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\services\ShoppingCartService;

class CartController
{
    public function listProducts(Request $request, Response $response): Response
    {
        $products = ShoppingCartService::getAvailableProducts();
        $response->getBody()->write(json_encode(['products' => $products], JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function calculateTotal(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();
        $result = ShoppingCartService::getTotalCartStatic(is_array($body) ? $body : []);
        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
