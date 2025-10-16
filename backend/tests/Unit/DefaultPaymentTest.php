<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\services\payment\DefaultPayment;

final class DefaultPaymentTest extends TestCase
{
    public function testDefaultReturnsPrincipal(): void
    {
        $p = 123.45;
        $d = new DefaultPayment();
        $res = $d->calculate($p);
        $this->assertEquals($p, $res['total_amount']);
        $this->assertEquals('NO_RULES_APPLIED', $res['details']['type']);
    }
}
