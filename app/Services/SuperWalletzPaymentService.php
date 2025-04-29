<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SuperWalletzPaymentService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.payment_providers.superwalletz.url');
    }

    public function processPayment(float $amount, string $currency, string $callbackUrl): array
    {

        $response = Http::post($this->baseUrl . '/pay', [
            'amount' => $amount,
            'currency' => $currency,
            'callback_url' => $callbackUrl
        ]);

        return $response->json();
    }
}
