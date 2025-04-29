<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EasyMoneyPaymentService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.payment_providers.easymoney.url');
    }

    public function processPayment(float $amount, string $currency)
    {
        $response = Http::post($this->baseUrl . '/process', [
            'amount' => $amount,
            'currency' => $currency
        ]);

        return $response->body();
    }
}
