<?php

namespace App\services\payment;

class PaymentFactory
{
    /**
     * Retorna uma instância de PaymentRuleInterface para o método informado.
     *
     * @param string $metodo
     * @return PaymentRuleInterface
     */
    public function create(string $method): PaymentRuleInterface
    {
        switch (strtoupper($method)) {
            case 'PIX':
                return new PixPayment();
            case 'CARTAO_CREDITO':
            case 'CARTÃO_CREDITO':
            case 'CREDIT_CARD':
                return new CreditCardPayment();
            default:
                return new DefaultPayment();
        }
    }
}
