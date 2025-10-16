<?php

require __DIR__ . '/../vendor/autoload.php';

use App\services\ShoppingCartService;

function printResult($title, $payload)
{
    $res = ShoppingCartService::getTotalCart($payload);
    echo "=== $title ===\n";
    echo "Principal: " . number_format($res['principal'], 2, ',', '.') . "\n";
    echo "Payment Method: " . $res['payment_method'] . "\n";
    echo "Installments: " . $res['installments'] . "\n";
    echo "Total Amount: " . number_format($res['total_amount'], 2, ',', '.') . "\n";
    echo "Details: " . json_encode($res['details'], JSON_UNESCAPED_UNICODE) . "\n\n";
}

$products = [
    ['name' => 'Fone Bluetooth', 'price' => 100.00, 'quantity' => 1],
    ['name' => 'Mouse Gamer', 'price' => 150.00, 'quantity' => 1],
];

// Principal esperado: 100 + 150 = 250
printResult('PIX (10% discount)', ['products' => $products, 'payment_method' => 'PIX']);
printResult('Credit card 1x (10% discount)', ['products' => $products, 'payment_method' => 'CARTAO_CREDITO', 'installments' => 1]);
printResult('Credit card 3x (compound 1%/month)', ['products' => $products, 'payment_method' => 'CARTAO_CREDITO', 'installments' => 3]);
printResult('Credit card 12x (compound 1%/month)', ['products' => $products, 'payment_method' => 'CARTAO_CREDITO', 'installments' => 12]);

echo "Manual checks finished.\n";
