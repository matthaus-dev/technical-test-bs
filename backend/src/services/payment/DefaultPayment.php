<?php

namespace App\services\payment;

use App\services\payment\PaymentRuleInterface;

class DefaultPayment implements PaymentRuleInterface
{
    public function calculate(float $principal, array $options = []): array
    {
        $details = ['type' => 'NO_RULES_APPLIED', 'tipo' => 'SEM_REGRAS_APLICAVEIS'];
        return [
            'total_amount' => $principal,
            'details' => $details,
        ];
    }
}
