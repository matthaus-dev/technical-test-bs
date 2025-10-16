<?php

namespace App\services\payment;

interface PaymentRuleInterface
{
    /**
     * Calculate the total amount based on the principal and additional options.
     *
     * Contract (ENGLISH-only):
     * - Input options: ['installments' => int]
     * - Return: ['total_amount' => float, 'details' => array]
     *
     * @param float $principal
     * @param array $options
     * @return array ['total_amount' => float, 'details' => array]
     */
    public function calculate(float $principal, array $options = []): array;
}
