<?php

namespace App\Contracts;

use Illuminate\Support\Carbon;

interface FinancialMetricsServiceInterface
{
    /**
     * Compute all financial metrics for a given period
     *
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @return array Array containing pmre, pmrv, pmpf metrics
     */
    public function compute(Carbon $from, Carbon $to): array;

    /**
     * Calculate PMRE (Prazo Médio de Renovação de Estoque)
     * Average Inventory Turnover Period
     *
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @return float Average days
     */
    public function pmre(Carbon $from, Carbon $to): float;

    /**
     * Calculate PMRV (Prazo Médio de Recebimento de Vendas)
     * Average Receivables Collection Period
     *
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @return float Average days
     */
    public function pmrv(Carbon $from, Carbon $to): float;

    /**
     * Calculate PMPF (Prazo Médio de Pagamento a Fornecedores)
     * Average Payables Payment Period
     *
     * @param Carbon $from Start date
     * @param Carbon $to End date
     * @return float Average days
     */
    public function pmpf(Carbon $from, Carbon $to): float;

    /**
     * Calculate operating and cash cycles
     *
     * @param float $pmre Average inventory turnover period
     * @param float $pmrv Average receivables collection period
     * @param float $pmpf Average payables payment period
     * @return array Array with operating_cycle and cash_cycle
     */
    public function cycles(float $pmre, float $pmrv, float $pmpf): array;

    /**
     * Calculate minimum cash needed based on cash cycle
     *
     * @param float $cashCycleDays Number of days in cash cycle
     * @param float $expenseForecastYear Annual expense forecast
     * @return float|null Minimum cash needed or null if invalid
     */
    public function minCash(float $cashCycleDays, float $expenseForecastYear): ?float;
}
