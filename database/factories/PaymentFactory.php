<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'currency' => 'USD',
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed']),
            'provider' => $this->faker->randomElement(['easymoney', 'superwalletz']),
            'description' => $this->faker->sentence(),
            'customer_email' => $this->faker->email(),
            'transaction_id' => 'txn_' . $this->faker->uuid(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
