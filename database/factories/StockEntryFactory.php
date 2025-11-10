<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockEntry>
 */
class StockEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entry_date' => fake()->dateTimeBetween('-120 days', '-60 days'),
            'quantity' => fake()->numberBetween(10, 500),
            'purchase_price' => fake()->randomFloat(2, 5, 250),
            'supplier_payment_terms' => fake()->randomElement([15, 30, 45, 60]),
            'invoice_number' => fake()->unique()->numerify('NF-#####'),
            'invoice_series' => fake()->randomElement(['1', 'A', 'B', 'C']),
            'warehouse' => fake()->randomElement(['Main', 'Backroom', 'Yard']),
        ];
    }
}
