<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Cement', 'Paint', 'Steel', 'Masonry', 'Hydraulics', 'Electrical', 'Tools', 'Lumber'];
        $brands = ['Votorantim', 'Suvinil', 'Gerdau', 'Construbrick', 'Tigre', 'Prysmian', 'Bosch', 'Makita', 'Dewalt', 'MadeiraBoa'];
        $products = [
            'Cement' => ['Cement CP-II 50kg', 'Mortar 20kg', 'White Cement 5kg'],
            'Paint' => ['Acrylic Paint 18L', 'Epoxy Paint 3.6L', 'Varnish 900ml'],
            'Steel' => ['8mm Rebar (12m)', '10mm Rebar (12m)', 'Welded Mesh 20x20cm'],
            'Masonry' => ['Hollow Brick 15x19x39', 'Solid Brick', 'Glass Block 19x19cm'],
            'Hydraulics' => ['PVC Pipe 50mm (3m)', 'PVC Elbow 90deg 50mm', 'Water Tap'],
            'Electrical' => ['Electrical Cable 2.5mm (100m)', 'Outlet 4x2', 'Circuit Breaker 20A'],
            'Tools' => ['Hammer', 'Screwdriver Set', 'Power Drill'],
            'Lumber' => ['Pine Board 2.5x30cm (3m)', 'Eucalyptus Rafter 5x10cm (3m)'],
        ];

        $category = fake()->randomElement($categories);
        $productName = fake()->randomElement($products[$category]);
        $brand = fake()->randomElement($brands);

        $cost = fake()->randomFloat(2, 2, 300);

        return [
            'name' => $productName,
            'sku' => fake()->unique()->bothify('???-######'),
            'barcode' => fake()->unique()->ean13(),
            'unit' => 'UN',
            'category' => $category,
            'brand' => $brand,
            'avg_cost' => $cost,
            'sale_price' => $cost * fake()->randomFloat(2, 1.2, 1.8),
            'min_stock' => fake()->numberBetween(10, 50),
            'is_active' => true,
        ];
    }
}
