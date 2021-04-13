<?php

namespace Omnipay\PayPal;

use Omnipay\Common\AbstractGateway;

/**
 * Paypal Gateway
 * @link https://www.paypal-support.com/
 */

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'PayPal';
    }

    public function getDefaultParameters()
    {
        return [
            'clientId' => '',
            'secret' => '',
            'token' => '',
            'merchantCurrency' => '',
        ];
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getMerchantCurrency()
    {
        return $this->getParameter('merchantCurrency');
    }

    public function setMerchantCurrency($value)
    {
        return $this->setParameter('merchantCurrency', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function createCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\CreateCardRequest', $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\DeleteCardRequest', $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\PurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RefundRequest', $parameters);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\AuthorizeRequest', $parameters);
    }

    public function void(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\VoidRequest', $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\CaptureRequest', $parameters);
    }

    public function refundCapture(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\PayPal\Message\RefundCaptureRequest', $parameters);
    }
}

