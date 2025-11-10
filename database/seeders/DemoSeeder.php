<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ManagementSetting;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockEntry;
use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            $sup1 = \App\Models\Supplier::create([
                'name' => 'Cimento Brasil',
                'tax_id' => '12.345.678/0001-99',
                'email' => 'contato@cimentobrasil.com',
                'phone' => '(27) 3333-1111',
                'address' => 'Av. Central, 123',
                'city' => 'Colatina',
                'state' => 'ES',
                'zip' => '29700-000',
                'country' => 'BR',
            ]);

            $sup2 = \App\Models\Supplier::create([
                'name' => 'Tintas Vitória',
                'tax_id' => '98.765.432/0001-10',
                'email' => 'vendas@tintasvitoria.com',
                'phone' => '(27) 2222-4444',
                'address' => 'Rua das Cores, 55',
                'city' => 'Vitória',
                'state' => 'ES',
                'zip' => '29000-000',
                'country' => 'BR',
            ]);

            $sup3 = \App\Models\Supplier::create([
                'name' => 'Ferraço Ferragens',
                'tax_id' => '11.222.333/0001-00',
                'email' => 'suporte@ferraco.com',
                'phone' => '(27) 4002-8922',
                'address' => 'Av. Industrial, 500',
                'city' => 'Serra',
                'state' => 'ES',
                'zip' => '29160-000',
                'country' => 'BR',
            ]);

            $cust1 = Customer::create([
                'name' => 'Construtora Alpha LTDA',
                'tax_id' => '12.345.678/0001-99',
                'email' => 'compras@alpha.com.br',
                'phone' => '(27) 3333-1001',
                'address' => 'Av. Brasil, 1200',
                'city' => 'Colatina',
                'state' => 'ES',
                'zip' => '29700-000',
                'country' => 'BR',
            ]);

            $cust2 = Customer::create([
                'name' => 'João Pereira',
                'tax_id' => '123.456.789-10',
                'email' => 'joaopereira@gmail.com',
                'phone' => '(27) 98888-2222',
                'address' => 'Rua das Palmeiras, 45',
                'city' => 'Baixo Guandu',
                'state' => 'ES',
                'zip' => '29730-000',
                'country' => 'BR',
            ]);

            $cust3 = Customer::create([
                'name' => 'Servitec Manutenções Industriais',
                'tax_id' => '98.765.432/0001-55',
                'email' => 'financeiro@servitec.com.br',
                'phone' => '(27) 99999-8899',
                'address' => 'Rod. do Contorno, km 5',
                'city' => 'Linhares',
                'state' => 'ES',
                'zip' => '29900-000',
                'country' => 'BR',
            ]);

            $p1 = Product::create([
                'name' => 'Cement CP-II 50kg',
                'sku' => 'CEM-CP2-50KG',
                'barcode' => '7890001112223',
                'unit' => 'UN',
                'category' => 'Cement',
                'brand' => 'Votorantim',
                'avg_cost' => 32.50,
                'sale_price' => 39.90,
                'min_stock' => 50,
            ]);
            $p2 = Product::create([
                'name' => 'Acrylic Paint 18L',
                'sku' => 'PAINT-ACR-18L',
                'barcode' => '7899991112220',
                'unit' => 'UN',
                'category' => 'Paint',
                'brand' => 'Suvinil',
                'avg_cost' => 180.00,
                'sale_price' => 259.90,
                'min_stock' => 10,
            ]);
            $p3 = Product::create([
                'name' => '8mm Rebar (12m)',
                'sku' => 'REBAR-8MM-12M',
                'barcode' => null,
                'unit' => 'UN',
                'category' => 'Steel',
                'brand' => 'Gerdau',
                'avg_cost' => 84.00,
                'sale_price' => 109.90,
                'min_stock' => 30,
            ]);
            $p4 = Product::create([
                'name' => 'Hollow Brick 15x19x39',
                'sku' => 'BRICK-15X19X39',
                'barcode' => null,
                'unit' => 'UN',
                'category' => 'Masonry',
                'brand' => 'Construbrick',
                'avg_cost' => 2.10,
                'sale_price' => 3.49,
                'min_stock' => 1000,
            ]);
            $p5 = Product::create([
                'name' => 'PVC Pipe 50mm (3m)',
                'sku' => 'PVC-50-3M',
                'barcode' => null,
                'unit' => 'UN',
                'category' => 'Hydraulics',
                'brand' => 'Tigre',
                'avg_cost' => 24.00,
                'sale_price' => 39.90,
                'min_stock' => 40,
            ]);
            $p6 = Product::create([
                'name' => 'Electrical Cable 2.5mm (100m)',
                'sku' => 'CABLE-2_5-100',
                'barcode' => null,
                'unit' => 'UN',
                'category' => 'Electrical',
                'brand' => 'Prysmian',
                'avg_cost' => 210.00,
                'sale_price' => 329.90,
                'min_stock' => 15,
            ]);

            $e1 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-01'),
                'supplier_id' => $sup1->id,
                'product_id' => $p1->id,
                'quantity' => 500,
                'purchase_price' => 30.00,
                'supplier_payment_terms' => 15,
                'invoice_number' => 'NF-1001',
                'invoice_series' => '1',
                'warehouse' => 'Main',
            ]);
            $e2 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-03'),
                'supplier_id' => $sup2->id,
                'product_id' => $p2->id,
                'quantity' => 80,
                'purchase_price' => 180.00,
                'supplier_payment_terms' => 20,
                'invoice_number' => 'NF-205',
                'invoice_series' => 'A',
                'warehouse' => 'Main',
            ]);
            $e3 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-05'),
                'supplier_id' => $sup3->id,
                'product_id' => $p3->id,
                'quantity' => 120,
                'purchase_price' => 78.00,
                'supplier_payment_terms' => 30,
                'invoice_number' => 'NF-5580',
                'invoice_series' => 'B',
                'warehouse' => 'Backroom',
            ]);
            $e4 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-08'),
                'supplier_id' => $sup1->id,
                'product_id' => $p4->id,
                'quantity' => 5000,
                'purchase_price' => 1.95,
                'supplier_payment_terms' => 10,
                'invoice_number' => 'NF-1009',
                'invoice_series' => '1',
                'warehouse' => 'Yard',
            ]);
            $e5 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-10'),
                'supplier_id' => $sup3->id,
                'product_id' => $p5->id,
                'quantity' => 150,
                'purchase_price' => 21.50,
                'supplier_payment_terms' => 25,
            ]);
            $e6 = StockEntry::create([
                'entry_date' => Carbon::parse('2025-10-12'),
                'supplier_id' => $sup3->id,
                'product_id' => $p6->id,
                'quantity' => 40,
                'purchase_price' => 199.00,
                'supplier_payment_terms' => 28,
            ]);

            $s1 = Sale::create([
                'sale_date' => Carbon::parse('2025-10-15'),
                'customer_id' => $cust1->id,
                'payment_method' => 'credit',
                'custom_terms' => 30,
                'installments' => 3,
                'status' => 'confirmed',
                'discount_total' => 50.00,
                'surcharge_total' => 0,
                'notes' => 'Pedido Alpha para obra Centro'
            ]);
            $s1->items()->createMany([
                ['product_id' => $p1->id, 'quantity' => 120, 'unit_price' => 39.90, 'discount' => 0],
                ['product_id' => $p2->id, 'quantity' => 10, 'unit_price' => 259.90, 'discount' => 20],
            ]);
            $s1->recalcTotals();

            $s2 = Sale::create([
                'sale_date' => Carbon::parse('2025-10-18'),
                'customer_id' => $cust2->id,
                'payment_method' => 'pix',
                'installments' => 1,
                'status' => 'confirmed',
                'discount_total' => 10.00,
                'surcharge_total' => 0,
                'notes' => 'Reforma residencial'
            ]);
            $s2->items()->createMany([
                ['product_id' => $p4->id, 'quantity' => 300, 'unit_price' => 3.49, 'discount' => 0],
                ['product_id' => $p5->id, 'quantity' => 8, 'unit_price' => 39.90, 'discount' => 0],
            ]);
            $s2->recalcTotals();

            $s3 = Sale::create([
                'sale_date' => Carbon::parse('2025-10-22'),
                'customer_id' => $cust3->id,
                'payment_method' => 'credit',
                'custom_terms' => 45,
                'installments' => 2,
                'status' => 'confirmed',
                'discount_total' => 0,
                'surcharge_total' => 0,
                'notes' => 'Tubulação e cabos para obra Beta'
            ]);
            $s3->items()->createMany([
                ['product_id' => $p5->id, 'quantity' => 40, 'unit_price' => 39.90, 'discount' => 0],
                ['product_id' => $p6->id, 'quantity' => 5, 'unit_price' => 329.90, 'discount' => 0],
            ]);
            $s3->recalcTotals();

            ManagementSetting::create([
                'expense_forecast' => 150000,
                'reference_period' => '2025-Q4',
                'credit_card_default_terms' => 30,
                'pix_debit_default_terms' => 0,
                'safety_stock_days' => 7,
            ]);
        });
    }
}
