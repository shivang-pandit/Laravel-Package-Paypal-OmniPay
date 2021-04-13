<?php

namespace  Omnipay\PayPal\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    const API_VERSION = 'v1';
    protected $endPoint = null;
    protected $testEndpoint = 'https://api.sandbox.paypal.com';
    protected $liveEndpoint = 'https://api.paypal.com';

    public function getEndpoint()
    {
        $endPoint = $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        return $this->endPoint = $endPoint . '/' .self::API_VERSION;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function getMerchantCurrency()
    {
        return $this->getParameter('merchantCurrency');
    }

    public function setMerchantCurrency($value)
    {
        return $this->setParameter('merchantCurrency', $value);
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

    public function getOrderNumber()
    {
        return $this->getParameter('order_number');
    }

    public function setOrderNumber($value)
    {
        return $this->setParameter('order_number', $value);
    }

    public function getDescription()
    {
        return $this->getParameter('description');
    }

    public function setDescription($value)
    {
        return $this->setParameter('description', $value);
    }

    public function getPaymentMethod()
    {
        return $this->getParameter('payment_method');
    }

    public function setPaymentMethod($value)
    {
        return $this->setParameter('payment_method', $value);
    }

    public function getHeaders()
    {

        $httpMethod = 'POST';
        $endPoint= $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
        $endPoint = $endPoint. '/' .self::API_VERSION .'/oauth2/token' ;

        $body = http_build_query(['grant_type' => 'client_credentials'], '', '&');

        $httpResponse = $this->httpClient->request(
            $httpMethod,
            $endPoint,
            [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("{$this->getClientId()}:{$this->getSecret()}"),
            ],
            $body
        );

        $response = (string) $httpResponse->getBody()->getContents();

        $jsonToArrayResponse = !empty($response) ? json_decode($response, true) : [];
        $token = isset($jsonToArrayResponse['access_token']) ? $jsonToArrayResponse['access_token'] : '';

        return [
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
    }

    public function sendData($data)
    {
        $headers = $this->getHeaders();

        if (!empty($data)) {
            $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers, $data);
        }
        else {
            $httpResponse = $this->httpClient->request($this->getHttpMethod(), $this->getEndpoint(), $headers);
        }

        try {
            $jsonRes = json_decode($httpResponse->getBody()->getContents(), true);
        }
        catch (\Exception $e){
            info('Guzzle response : ', [$httpResponse]);
            $res = [];
            $res['resptext'] = 'Oops! something went wrong, Try again after sometime.';
            return $this->response = new Response($this, $res);
        }

        return $this->response = new Response($this, $jsonRes, $httpResponse->getStatusCode());
    }
}

