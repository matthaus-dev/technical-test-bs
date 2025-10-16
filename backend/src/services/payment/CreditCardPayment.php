<?php

namespace App\services\payment;

use App\services\payment\PaymentRuleInterface;

class CreditCardPayment implements PaymentRuleInterface
{
    /**
     * Espera em $options['parcelas'] o número de parcelas.
     */
    public function calculate(float $principal, array $options = []): array
    {
        // Accept both English and Portuguese option names
        $installments = intval($options['installments'] ?? $options['parcelas'] ?? 1);

        if ($installments <= 1) {
            // treat as single payment (no interest) -> here we apply 10% discount
            $totalAmount = round($principal * 0.90, 2);
            $details = ['type' => 'DISCOUNT_10', 'discount_percent' => 10, 'installments' => $installments, 'tipo' => 'DESCONTO_10'];
        } elseif ($installments >= 2 && $installments <= 12) {
            $monthlyRate = 0.01; // 1% monthly interest
            $periods = $installments;
            $amount = $principal * pow(1 + $monthlyRate, $periods);
            $totalAmount = round($amount, 2);
            $details = ['type' => 'COMPOUND_INTEREST', 'monthly_interest_percent' => 1, 'installments' => $installments, 'tipo' => 'JUROS_COMPOSTOS'];
        } else {
            // fallback: no change
            $totalAmount = $principal;
            $details = ['type' => 'NO_RULES_APPLIED', 'installments' => $installments, 'tipo' => 'SEM_REGRAS_APLICAVEIS'];
        }

        return [
            'total_amount' => $totalAmount,
            'details' => $details,
        ];
    }
}
