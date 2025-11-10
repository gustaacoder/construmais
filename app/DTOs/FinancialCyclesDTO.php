<?php

namespace App\DTOs;

class FinancialCyclesDTO
{
    public function __construct(
        public readonly float $pmre,
        public readonly float $pmrv,
        public readonly float $pmpf,
        public readonly float $operatingCycle,
        public readonly float $cashCycle,
        public readonly ?float $minimumCash = null
    ) {}

    /**
     * Create from individual metrics
     *
     * @param float $pmre Average inventory turnover period
     * @param float $pmrv Average receivables collection period
     * @param float $pmpf Average payables payment period
     * @param float|null $annualExpenses Optional annual expenses for minimum cash calculation
     * @return self
     */
    public static function fromMetrics(
        float $pmre,
        float $pmrv,
        float $pmpf,
        ?float $annualExpenses = null
    ): self {
        $operatingCycle = round($pmre + $pmrv, 2);
        $cashCycle = round($operatingCycle - $pmpf, 2);
        
        $minimumCash = null;
        if ($annualExpenses !== null && $cashCycle > 0) {
            $minimumCash = round($annualExpenses / ($cashCycle / 360), 2);
        }

        return new self(
            pmre: round($pmre, 2),
            pmrv: round($pmrv, 2),
            pmpf: round($pmpf, 2),
            operatingCycle: $operatingCycle,
            cashCycle: $cashCycle,
            minimumCash: $minimumCash
        );
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'pmre' => $this->pmre,
            'pmrv' => $this->pmrv,
            'pmpf' => $this->pmpf,
            'operating_cycle' => $this->operatingCycle,
            'cash_cycle' => $this->cashCycle,
        ];

        if ($this->minimumCash !== null) {
            $data['minimum_cash'] = $this->minimumCash;
        }

        return $data;
    }
}
