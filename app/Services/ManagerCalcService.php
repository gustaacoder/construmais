<?php

namespace App\Services;

use App\Models\{Sale, StockEntry, ManagementSetting};
use Illuminate\Support\Carbon;

class ManagerCalcService
{
    /**
     * Retorna PMRE, PMRV e PMPF para um período.
     */
    public function compute(Carbon $from, Carbon $to): array
    {
        $pmre = $this->pmre($from, $to);
        $pmrv = $this->pmrv($from, $to);
        $pmpf = $this->pmpf($from, $to);

        return compact('pmre', 'pmrv', 'pmpf');
    }

    /**
     * PMRE (Prazo Médio de Renovação de Estoques):
     * média dos dias entre a entrada do produto e a venda (por item vendido).
     */
    public function pmre(Carbon $from, Carbon $to): float
    {
        $sales = Sale::with('items')
            ->whereBetween('sale_date', [$from, $to])
            ->get();

        $days = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $it) {
                $entry = StockEntry::where('product_id', $it->product_id)
                    ->whereDate('entry_date', '<=', $sale->sale_date)
                    ->orderBy('entry_date') // heurística simples: 1ª entrada <= venda
                    ->first();

                if ($entry) {
                    $days[] = Carbon::parse($sale->sale_date)->diffInDays($entry->entry_date);
                }
            }
        }

        return $days ? round(array_sum($days) / count($days), 2) : 0.0;
    }

    /**
     * PMRV (Prazo Médio de Recebimento de Vendas):
     * usa termos padrão por forma de pagamento, podendo sobrescrever com custom_terms.
     */
    public function pmrv(Carbon $from, Carbon $to): float
    {
        // Padrões (ajuste se quiser)
        $map = ['pix' => 0, 'debit' => 0, 'credit' => 30];

        $sales = Sale::whereBetween('sale_date', [$from, $to])->get();
        if ($sales->isEmpty()) {
            return 0.0;
        }

        $avg = $sales->avg(function (Sale $s) use ($map) {
            return $s->custom_terms ?? ($map[$s->payment_method] ?? 0);
        });

        return round((float) $avg, 2);
    }

    /**
     * PMPF (Prazo Médio de Pagamento a Fornecedores):
     * média dos supplier_payment_terms das entradas no período.
     */
    public function pmpf(Carbon $from, Carbon $to): float
    {
        $terms = StockEntry::whereBetween('entry_date', [$from, $to])
            ->pluck('supplier_payment_terms');

        return $terms->isEmpty() ? 0.0 : round((float) $terms->avg(), 2);
    }

    /**
     * Ciclos: Operacional e de Caixa.
     * OC = PMRE + PMRV ;  CCC = OC - PMPF
     */
    public function cycles(float $pmre, float $pmrv, float $pmpf): array
    {
        $oper = $pmre + $pmrv;
        $cash = $oper - $pmpf;

        return [
            'operating_cycle' => round($oper, 2),
            'cash_cycle'      => round($cash, 2),
        ];
    }

    /**
     * Saldo Mínimo de Caixa:
     * SMC = Previsão de Gastos / (CCC / 360). Retorna null se CCC = 0.
     */
    public function minCash(float $cashCycleDays, float $expenseForecastYear): ?float
    {
        if ($cashCycleDays == 0.0) {
            return null;
        }
        return round($expenseForecastYear / ($cashCycleDays / 360), 2);
    }
}

