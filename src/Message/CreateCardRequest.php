<?php

namespace Omnipay\PayPal\Message;

use Omnipay\PayPal\Dictionary\CountryCodesDictionary;

class CreateCardRequest extends AbstractRequest
{
    public function getEndpoint()
    {
        return $this->endPoint = parent::getEndpoint() . '/vault/credit-cards';
    }

    public function getHttpMethod()
    {
        return 'POST';
    }

    public function getData()
    {
        $data = [];
        $card = $this->getCard();
        $card->validate();

        $country = $card->getBillingCountry();
        $countryCode = array_search(strtolower($country), array_map('strtolower', CountryCodesDictionary::$codes));
        if($card) {
            $data = [
                'number' => $card->getNumber(),
                'type' => $card->getBrand(),
                'expire_month' => $card->getExpiryMonth(),
                'expire_year' => $card->getExpiryYear(),
                'cvv2' => $card->getCvv(),
                'first_name' => $card->getFirstName(),
                'last_name' => $card->getLastName(),
                'billing_address' => [
                    'line1' => $card->getAddress1(),
                    //'line2' => $card->getAddress2(),
                    'city' => $card->getCity(),
                    'state' => $card->getState(),
                    'country_code' => strtoupper($countryCode),
                    'postal_code' => $card->getPostcode(),
                    'state' => $card->getState(),
                    'phone' => $card->getPhone(),
                ]
            ];
        }
        return json_encode($data);
    }
}
