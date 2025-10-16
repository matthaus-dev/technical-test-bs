<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\services\payment\PixPayment;

final class PixPaymentTest extends TestCase
{
    public function testPixApplies10PercentDiscount(): void
    {
        $p = 200.0;
        $pix = new PixPayment();
        $res = $pix->calculate($p);
        $this->assertEquals(180.0, $res['total_amount']);
        $this->assertEquals('DISCOUNT_10', $res['details']['type']);
    }
}
