<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = fake()->randomElement(['pix', 'debit', 'credit']);
        $customTerms = null;
        if ($paymentMethod === 'credit') {
            $customTerms = fake()->randomElement([15, 30, 45, 60, 90]);
        }

        return [
            'sale_date' => fake()->dateTimeBetween('-59 days', 'now'),
            'payment_method' => $paymentMethod,
            'custom_terms' => $customTerms,
            'installments' => $paymentMethod === 'credit' ? fake()->numberBetween(1, 12) : 1,
            'status' => 'confirmed',
            'discount_total' => fake()->randomFloat(2, 0, 100),
            'surcharge_total' => 0,
            'notes' => fake()->sentence(),
        ];
    }
}
