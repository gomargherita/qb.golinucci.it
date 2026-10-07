<?php

namespace App\Services\Payments;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalClientFactory
{
    public function make(): PayPalClient
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        return $provider;
    }
}
