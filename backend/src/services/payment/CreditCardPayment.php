<?php

namespace App\services\payment;

use App\services\payment\PaymentRuleInterface;

class CreditCardPayment implements PaymentRuleInterface
{    
    public function calculate(float $principal, array $options = []): array
    {        
        $installments = max(1, intval($options['installments'] ?? $options['parcelas'] ?? 1));

        if ($installments <= 1) {            
            $totalAmount = round($principal * 0.90, 2);
            $details = ['type' => 'DISCOUNT_10', 'discount_percent' => 10, 'installments' => $installments, 'tipo' => 'DESCONTO_10'];
        } elseif ($installments >= 2 && $installments <= 12) {
            $monthlyRate = 0.01;
            $periods = $installments;
            $amount = $principal * pow(1 + $monthlyRate, $periods);
            $totalAmount = round($amount, 2);
            $details = ['type' => 'COMPOUND_INTEREST', 'monthly_interest_percent' => 1, 'installments' => $installments, 'tipo' => 'JUROS_COMPOSTOS'];
        } else {
        
            $totalAmount = $principal;
            $details = ['type' => 'NO_RULES_APPLIED', 'installments' => $installments, 'tipo' => 'SEM_REGRAS_APLICAVEIS'];
        }
                
        $installmentValue = round($totalAmount / max(1, $installments), 2);

        return [
            'total_amount' => $totalAmount,
            'installments' => $installments,
            'installment_value' => $installmentValue,
            'details' => $details,
        ];
    }
}
