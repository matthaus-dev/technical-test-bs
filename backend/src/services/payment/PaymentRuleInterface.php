<?php

namespace App\services\payment;

interface PaymentRuleInterface
{
    /**
     * Calcula o valor total a pagar com base no valor principal e nas opões fornecidas.
     *      
     * @param float $principal
     * @param array $options
     * @return array ['total_amount' => float, 'details' => array]
     */
    public function calculate(float $principal, array $options = []): array;
}
