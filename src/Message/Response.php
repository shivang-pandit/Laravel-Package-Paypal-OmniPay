<?php

namespace Omnipay\PayPal\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class Response extends AbstractResponse
{
    protected $statusCode;

    public function __construct(RequestInterface $request, $data, $statusCode = 200)
    {
        parent::__construct($request, $data);
        $this->statusCode = $statusCode;
    }

    public function isSuccessful()
    {
        return empty($this->data['error']) && $this->getCode() < 400 && $this->data;
    }

    public function getTransactionReference()
    {
        if (!empty($this->data['transactions']) && !empty($this->data['transactions'][0]['related_resources'])) {
            foreach (array('sale', 'authorization') as $type) {
                if (!empty($this->data['transactions'][0]['related_resources'][0][$type])) {
                    return $this->data['transactions'][0]['related_resources'][0][$type]['id'];
                }
            }
        }

        if (!empty($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }

    public function getMessage()
    {
        if (isset($this->data['error_description'])) {
            return $this->data['error_description'];
        }

        if (isset($this->data['message'])) {
            return $this->data['message'];
        }

        return null;
    }

    public function getCode()
    {
        return $this->statusCode;
    }

    public function getCardReference()
    {
        $token = null;

        $data = $this->data;

        if (isset($data['id']) && $data['state'] == 'ok') {
            $data = [
                'token' => $data['id'],
                'expire_month' => $data['expire_month'],
                'expire_year' => $data['expire_year'],
                'type' => $data['type']
            ];
            $token = json_encode($data);
        }

        return $token;
    }

    public function getOrderNumber()
    {
        return isset($this->data['transactions'][0]['invoice_number']) ? $this->data['transactions'][0]['invoice_number'] : null;
    }

    public function getData()
    {
        return json_encode($this->data);
    }
}
