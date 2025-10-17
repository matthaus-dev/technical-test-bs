<?php

namespace App\controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\services\ShoppingCartService;

class CartController
{
    private ShoppingCartService $shoppingCartService;

    public function __construct(ShoppingCartService $shoppingCartService)
    {
        $this->shoppingCartService = $shoppingCartService;
    }

    public function listProducts(Request $request, Response $response): Response
    {
        $products = $this->shoppingCartService->getAvailableProducts();
        $response->getBody()->write(json_encode(['products' => $products], JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function calculateTotal(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        if (!is_array($body)) {
            $response->getBody()->write(json_encode(['error' => 'Invalid JSON payload.']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $errors = [];
        if (empty($body['products']) || !is_array($body['products'])) {
            $errors['products'] = 'The "products" field must be a non-empty array.';
        }

        if (empty($body['payment_method']) || !is_string($body['payment_method'])) {
            $errors['payment_method'] = 'The "payment_method" field is required and must be a string.';
        }

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $result = $this->shoppingCartService->getTotalCart($body);
        $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
