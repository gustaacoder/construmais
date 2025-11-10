<?php

namespace Tests\Unit\DTOs;

use App\DTOs\FinancialCyclesDTO;
use PHPUnit\Framework\TestCase;

class FinancialCyclesDTOTest extends TestCase
{
    public function test_can_create_from_metrics(): void
    {
        $dto = FinancialCyclesDTO::fromMetrics(
            pmre: 30.0,
            pmrv: 45.0,
            pmpf: 60.0
        );

        $this->assertEquals(30.0, $dto->pmre);
        $this->assertEquals(45.0, $dto->pmrv);
        $this->assertEquals(60.0, $dto->pmpf);
        $this->assertEquals(75.0, $dto->operatingCycle); // 30 + 45
        $this->assertEquals(15.0, $dto->cashCycle); // 75 - 60
        $this->assertNull($dto->minimumCash);
    }

    public function test_can_calculate_minimum_cash(): void
    {
        $dto = FinancialCyclesDTO::fromMetrics(
            pmre: 30.0,
            pmrv: 45.0,
            pmpf: 60.0,
            annualExpenses: 360000.0
        );

        // Cash cycle = 15 days
        // Min cash = 360000 / (15/360) = 360000 / 0.0417 = 8640000
        $this->assertNotNull($dto->minimumCash);
        $this->assertEquals(8640000.0, $dto->minimumCash);
    }

    public function test_can_convert_to_array(): void
    {
        $dto = FinancialCyclesDTO::fromMetrics(
            pmre: 30.0,
            pmrv: 45.0,
            pmpf: 60.0
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('pmre', $array);
        $this->assertArrayHasKey('pmrv', $array);
        $this->assertArrayHasKey('pmpf', $array);
        $this->assertArrayHasKey('operating_cycle', $array);
        $this->assertArrayHasKey('cash_cycle', $array);
        $this->assertArrayNotHasKey('minimum_cash', $array);
    }

    public function test_includes_minimum_cash_in_array_when_present(): void
    {
        $dto = FinancialCyclesDTO::fromMetrics(
            pmre: 30.0,
            pmrv: 45.0,
            pmpf: 60.0,
            annualExpenses: 360000.0
        );

        $array = $dto->toArray();

        $this->assertArrayHasKey('minimum_cash', $array);
    }
}
