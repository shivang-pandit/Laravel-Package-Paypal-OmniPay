<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class AuthorizeRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        return $this->endPoint = parent::getEndpoint() . '/payments/payment';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $this->validate('order_number', 'amount', 'payment_method');

        $data = [
            'intent' => 'authorize',
            'payer' => [
                'payment_method' => 'credit_card',
                'funding_instruments' => []
            ],
            'transactions' => [
                [
                    'description' => $this->getDescription(),
                    'amount' => [
                        'total' => $this->getAmount(),
                        'currency' => $this->getMerchantCurrency(),
                    ],
                    'invoice_number' => $this->getOrderNumber()
                ]
            ]
        ];

        $paymentMethod = $this->getPaymentMethod();

        switch ($paymentMethod) {
            case 'payment_profile':
                $this->validate('cardReference');

                try {
                    $cardReference = json_decode($this->getCardReference());
                    $token = $cardReference->token;
                }
                catch (\Exception $e) {
                    throw new InvalidRequestException('Invalid payment profile');
                }

                $data['payer']['funding_instruments'][] = [
                    'credit_card_token' => [
                        'credit_card_id' => $token,
                    ],
                ];
                break;

            // Todo: card & token payment

            default:
                throw new InvalidRequestException('Invalid payment method');
                break;
        }

        return json_encode($data);
    }
}

