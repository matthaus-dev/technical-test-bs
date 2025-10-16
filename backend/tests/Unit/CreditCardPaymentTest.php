<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\services\payment\CreditCardPayment;

final class CreditCardPaymentTest extends TestCase
{
    public function testCreditCardOneInstallmentAppliesDiscount(): void
    {
        $p = 300.0;
        $cc = new CreditCardPayment();
        $res = $cc->calculate($p, ['installments' => 1]);
        $this->assertEquals(270.0, $res['total_amount']);
        $this->assertEquals('DISCOUNT_10', $res['details']['type']);
    }

    public function testCreditCardMultipleInstallmentsApplyCompoundInterest(): void
    {
        $p = 100.0;
        $cc = new CreditCardPayment();
        $res = $cc->calculate($p, ['installments' => 2]);
        $expected = round($p * pow(1 + 0.01, 2), 2);
        $this->assertEquals($expected, $res['total_amount']);
        $this->assertEquals('COMPOUND_INTEREST', $res['details']['type']);
    }
}
