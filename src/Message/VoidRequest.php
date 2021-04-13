<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class VoidRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        $this->validate('transactionReference');
        try {
            $transactionReference = json_decode($this->getTransactionReference());
            $authorizeId = $transactionReference->transactions[0]->related_resources[0]->authorization->id;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid transaction reference');
        }

        return parent::getEndpoint() . '/payments/authorization/' . $authorizeId . '/void';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        return null;
    }
}

