<?php

namespace App\Observers;

use App\Models\Receivable;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SaleObserver
{
    public function saving(Sale $sale): void
    {
        if ($sale->exists) {
            $sale->recalcTotals();
        }
    }

    public function saved(Sale $sale): void
    {
        $sale->recalcTotals();

        if ($sale->status === 'confirmed') {
            $this->syncReceivables($sale);
        } else {
            $this->deleteOpenReceivables($sale);
        }
    }

    public function deleted(Sale $sale): void
    {
        $this->deleteOpenReceivables($sale);
    }

    protected function syncReceivables(Sale $sale): void
    {
        $relevantChanged = $sale->wasRecentlyCreated
            || $sale->wasChanged(['sale_date','installments','payment_method','custom_terms','grand_total','status']);

        if (! $relevantChanged) {
            return;
        }

        $hasPaid = $sale->receivables()->where('status', 'paid')->exists();
        if ($hasPaid) {
            return;
        }

        DB::transaction(function () use ($sale) {
            $sale->receivables()->whereIn('status', ['open','overdue'])->delete();

            $termsMap = ['pix' => 0, 'debit' => 0, 'credit' => 30];
            $firstTermDays = $sale->custom_terms ?? ($termsMap[$sale->payment_method] ?? 0);

            $installments = $sale->installments ?: 1;
            $amountEach   = $installments > 0 ? round($sale->grand_total / $installments, 2) : (float) $sale->grand_total;

            $firstDue = Carbon::parse($sale->sale_date)->addDays($firstTermDays);

            for ($i = 0; $i < $installments; $i++) {
                Receivable::create([
                    'sale_id'     => $sale->id,
                    'document_no' => sprintf('RCV-%06d-%02d', $sale->id, $i + 1),
                    'issue_date'  => $sale->sale_date,
                    'due_date'    => (clone $firstDue)->addMonths($i),
                    'amount'      => $amountEach,
                    'status'      => 'open',
                ]);
            }
        });
    }

    protected function deleteOpenReceivables(Sale $sale): void
    {
        $sale->receivables()->whereIn('status', ['open','overdue'])->delete();
    }
}
