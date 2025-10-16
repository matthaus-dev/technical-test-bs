<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\services\ShoppingCartService;

final class ShoppingCartServiceTest extends TestCase
{
    public function testIntegrationPix(): void
    {
        $svc = new ShoppingCartService();
        $payload = [
            'products' => [
                ['name' => 'A', 'price' => 50, 'quantity' => 1],
                ['name' => 'B', 'price' => 50, 'quantity' => 1],
            ],
            'payment_method' => 'PIX',
        ];

        $res = $svc->getTotalCart($payload);
        $this->assertEquals(100.0, $res['principal']);
        $this->assertEquals(90.0, $res['total_amount']);
    }
}
