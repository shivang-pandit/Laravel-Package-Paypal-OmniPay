<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $saleId = $transactionReference->transactions[0]->related_resources[0]->sale->id;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }

        return $this->endPoint = parent::getEndpoint() . '/payments/sale/' . $saleId . '/refund';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $this->validate('amount', 'transactionReference');

        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $currency = $transactionReference->transactions[0]->related_resources[0]->sale->amount->currency;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }

        $data = [
            'amount' => [
                'currency' => $currency,
                'total' => $this->getAmount(),
            ],
            'description' => $this->getDescription(),
        ];

        return json_encode($data);
    }
}
