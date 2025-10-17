<?php

namespace App\services\payment;

use App\services\payment\PaymentRuleInterface;

class PixPayment implements PaymentRuleInterface
{
    public function calculate(float $principal, array $options = []): array
    {       
        $total = round($principal * 0.90, 2);
        $details = ['type' => 'DISCOUNT_10', 'discount_percent' => 10, 'tipo' => 'DESCONTO_10'];
        return [
            'total_amount' => $total,
            'details' => $details,
        ];
    }
}
