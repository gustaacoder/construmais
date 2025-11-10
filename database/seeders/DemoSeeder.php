<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ManagementSetting;
use App\Models\Payable;
use App\Models\Product;
use App\Models\Receivable;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockEntry;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Temporarily disable foreign key checks
            Schema::disableForeignKeyConstraints();

            // Use query()->delete() for transaction safety
            Payable::query()->delete();
            Receivable::query()->delete();
            SaleItem::query()->delete();
            Sale::query()->delete();
            StockEntry::query()->delete();
            Product::query()->delete();
            Customer::query()->delete();
            Supplier::query()->delete();
            ManagementSetting::query()->delete();

            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();

            // Create base data
            $suppliers = Supplier::factory()->count(20)->create();
            $customers = Customer::factory()->count(50)->create();
            $products = Product::factory()->count(100)->create();

            // For each product, create a guaranteed history
            foreach ($products as $product) {
                // Create a stock entry for this product
                $entry = StockEntry::factory()->create([
                    'product_id' => $product->id,
                    'supplier_id' => $suppliers->random()->id,
                    'entry_date' => fake()->dateTimeBetween('-120 days', '-60 days'),
                    'quantity' => fake()->numberBetween(100, 500),
                ]);

                // Now, create a sale for the same product
                $sale = Sale::factory()->create([
                    'customer_id' => $customers->random()->id,
                    'sale_date' => fake()->dateTimeBetween(Carbon::parse($entry->entry_date)->addDays(5), 'now'),
                ]);

                // Manually create the sale item to ensure the link
                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => fake()->numberBetween(1, 20),
                    'unit_price' => $product->sale_price,
                    'discount' => 0,
                ]);
                // Manually trigger the observer logic
                $sale->recalcTotals();
                // The SaleObserver's `saved` method will handle receivables
            }

            // Create management settings
            ManagementSetting::create([
                'expense_forecast' => 150000,
                'reference_period' => '2025',
                'credit_card_default_terms' => 30,
                'pix_debit_default_terms' => 0,
                'safety_stock_days' => 7,
            ]);
        });
    }
}
