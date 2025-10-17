<?php

namespace App\services;

use App\services\payment\PaymentFactory;

class ShoppingCartService
{
    private PaymentFactory $factory;

    public function __construct(?PaymentFactory $factory = null)
    {
        $this->factory = $factory ?? new PaymentFactory();
    }

    public static function getAvailableProducts(): array
    {        
        return [
            ['name' => 'Fone Bluetooth', 'price' => 100.00],
            ['name' => 'Mouse Gamer', 'price' => 150.00],
            ['name' => 'Teclado Gamer', 'price' => 250.00],
            ['name' => 'Monitor 24"', 'price' => 900.00],
            ['name' => 'Webcam HD', 'price' => 200.00],
        ];
    }

    public function getTotalCart(array $payload): array
    {        
        $products = $payload['products'] ?? [];
        $paymentMethod = strtoupper($payload['payment_method'] ?? 'PIX');
        $installments = intval($payload['installments'] ?? 1);

        $principal = 0.0;
        foreach ($products as $product) {
            $price = floatval($product['price'] ?? 0);
            $quantity = intval($product['quantity'] ?? 1);
            $principal += $price * $quantity;
        }
        $principal = round($principal, 2);

        $paymentRule = $this->factory->create($paymentMethod);

        $result = $paymentRule->calculate($principal, ['installments' => $installments]);

        $totalAmount = $result['total_amount'] ?? $principal;
        $details = $result['details'] ?? [];
        $intallmentValue = $result['installment_value'] ?? $totalAmount;

        return [
            'total_amount' => $totalAmount,
            'principal' => $principal,
            'payment_method' => $paymentMethod,
            'installments' => $installments,
            'installment_value' => $intallmentValue,
            'details' => $details,
        ];
    }
}
