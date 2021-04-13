<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class DeleteCardRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        $this->validate('cardReference');
        try {
            $cardReference = json_decode($this->getCardReference());
            $token = $cardReference->token;
        }
        catch (\Exception $e) {
            throw new InvalidRequestException('Invalid card reference');
        }
        return $this->endPoint = parent::getEndpoint() . '/vault/credit-cards/' . $token;
    }

    public function getHttpMethod()
    {
        return 'DELETE';
    }

    public function getData()
    {
        return null;
    }
}
