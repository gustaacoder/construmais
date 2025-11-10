<?php

namespace Tests\Unit\DTOs;

use App\DTOs\SaleTotalsDTO;
use PHPUnit\Framework\TestCase;

class SaleTotalsDTOTest extends TestCase
{
    public function test_can_calculate_totals_from_sale_data(): void
    {
        $items = [
            ['quantity' => 2, 'unit_price' => 100.00, 'discount' => 10.00],
            ['quantity' => 1, 'unit_price' => 50.00, 'discount' => 0.00],
        ];

        $dto = SaleTotalsDTO::fromSaleData(
            items: $items,
            discountTotal: 20.00,
            surchargeTotal: 15.00
        );

        $this->assertEquals(240.00, $dto->subtotal); // (2*100-10) + (1*50-0) = 190 + 50
        $this->assertEquals(20.00, $dto->discountTotal);
        $this->assertEquals(15.00, $dto->surchargeTotal);
        $this->assertEquals(235.00, $dto->grandTotal); // 240 - 20 + 15
    }

    public function test_handles_negative_totals_correctly(): void
    {
        $items = [
            ['quantity' => 1, 'unit_price' => 10.00, 'discount' => 0.00],
        ];

        $dto = SaleTotalsDTO::fromSaleData(
            items: $items,
            discountTotal: 50.00,
            surchargeTotal: 0.00
        );

        $this->assertEquals(0.00, $dto->grandTotal); // Should not be negative
    }

    public function test_can_convert_to_array(): void
    {
        $items = [
            ['quantity' => 1, 'unit_price' => 100.00, 'discount' => 0.00],
        ];

        $dto = SaleTotalsDTO::fromSaleData($items);

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('subtotal', $array);
        $this->assertArrayHasKey('discount_total', $array);
        $this->assertArrayHasKey('surcharge_total', $array);
        $this->assertArrayHasKey('grand_total', $array);
    }
}
