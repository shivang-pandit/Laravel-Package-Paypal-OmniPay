<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class RefundCaptureRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $captureId = $transactionReference->id;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }
        return $this->endPoint = parent::getEndpoint() . '/payments/capture/' . $captureId . '/refund';
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
            $currency = $transactionReference->amount->currency;
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
