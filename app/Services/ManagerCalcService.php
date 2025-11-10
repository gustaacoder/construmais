<?php

namespace App\Services;

use App\Models\{Sale, StockEntry, ManagementSetting, Payable, Receivable};
use Illuminate\Support\Carbon;

class ManagerCalcService
{
    public function compute(Carbon $from, Carbon $to): array
    {
        $pmre = $this->pmre($from, $to);
        $pmrv = $this->pmrv($from, $to);
        $pmpf = $this->pmpf($from, $to);

        return compact('pmre', 'pmrv', 'pmpf');
    }

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
                    ->orderBy('entry_date', 'desc')
                    ->first();

                if ($entry) {
                    $saleDate = Carbon::parse($sale->sale_date);
                    $entryDate = Carbon::parse($entry->entry_date);
                    $days[] = round(($saleDate->timestamp - $entryDate->timestamp) / 86400);
                }
            }
        }

        return $days ? round(array_sum($days) / count($days), 2) : 0.0;
    }

    public function pmrv(Carbon $from, Carbon $to): float
    {
        $receivables = Receivable::whereBetween('issue_date', [$from, $to])->get();

        if ($receivables->isEmpty()) {
            return 0.0;
        }

        $avg = $receivables->avg(function (Receivable $r) {
            $issueDate = Carbon::parse($r->issue_date);
            $dueDate = Carbon::parse($r->due_date);
            return ($dueDate->timestamp - $issueDate->timestamp) / 86400;
        });

        return round((float) $avg, 2);
    }

    public function pmpf(Carbon $from, Carbon $to): float
    {
        $payables = Payable::whereBetween('issue_date', [$from, $to])->get();

        if ($payables->isEmpty()) {
            return 0.0;
        }

        $avg = $payables->avg(function (Payable $p) {
            $issueDate = Carbon::parse($p->issue_date);
            $dueDate = Carbon::parse($p->due_date);
            return ($dueDate->timestamp - $issueDate->timestamp) / 86400;
        });

        return round((float) $avg, 2);
    }

    public function cycles(float $pmre, float $pmrv, float $pmpf): array
    {
        $oper = $pmre + $pmrv;
        $cash = $oper - $pmpf;

        return [
            'operating_cycle' => round($oper, 2),
            'cash_cycle'      => round($cash, 2),
        ];
    }

    public function minCash(float $cashCycleDays, float $expenseForecastYear): ?float
    {
        if ($cashCycleDays == 0.0) {
            return null;
        }
        return round($expenseForecastYear / ($cashCycleDays / 360), 2);
    }
}

