<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class CaptureRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $authorizeId = $transactionReference->transactions[0]->related_resources[0]->authorization->id;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }

        return $this->endPoint = parent::getEndpoint() . '/payments/authorization/'.$authorizeId.'/capture';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $this->validate('transactionReference');

        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $total = $transactionReference->transactions[0]->related_resources[0]->authorization->amount->total;
            $currency = $transactionReference->transactions[0]->related_resources[0]->authorization->amount->currency;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }

        $data = [
            'amount' => [
                'currency' => $currency,
                'total' => $total,
            ],
            'is_final_capture' => true,
        ];

        return json_encode($data);
    }
}

