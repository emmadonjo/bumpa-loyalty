<?php

namespace Database\Factories;

use App\Domains\Store\Persistence\Entities\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Model>
 */
class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomFloat(2, 1_000, 10_000),
            'purchased_at' => $this->faker->date(),
            'description' => $this->faker->text(),
            'reference' => $this->faker->uuid(),
            'provider' => 'paystack'
        ];
    }
}
