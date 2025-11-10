<?php

namespace App\Actions;

use App\Models\Receivable;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CreateReceivablesAction
{
    /**
     * Create receivables for a confirmed sale
     *
     * @param Sale $sale The sale to create receivables for
     * @return void
     */
    public function execute(Sale $sale): void
    {
        // Check if already has paid receivables
        $hasPaid = $sale->receivables()->where('status', 'paid')->exists();
        if ($hasPaid) {
            return;
        }

        DB::transaction(function () use ($sale) {
            // Delete existing open/overdue receivables
            $sale->receivables()->whereIn('status', ['open', 'overdue'])->delete();

            $termsMap = ['pix' => 0, 'debit' => 0, 'credit' => 30];
            $firstTermDays = $sale->custom_terms ?? ($termsMap[$sale->payment_method] ?? 0);

            $installments = $sale->installments ?: 1;
            $amountEach = $installments > 0 
                ? round($sale->grand_total / $installments, 2) 
                : (float) $sale->grand_total;

            $firstDue = Carbon::parse($sale->sale_date)->addDays($firstTermDays);

            for ($i = 0; $i < $installments; $i++) {
                Receivable::create([
                    'sale_id' => $sale->id,
                    'document_no' => sprintf('RCV-%06d-%02d', $sale->id, $i + 1),
                    'issue_date' => $sale->sale_date,
                    'due_date' => (clone $firstDue)->addMonths($i),
                    'amount' => $amountEach,
                    'status' => 'open',
                ]);
            }
        });
    }

    /**
     * Delete open or overdue receivables for a sale
     *
     * @param Sale $sale The sale to delete receivables for
     * @return void
     */
    public function deleteOpenReceivables(Sale $sale): void
    {
        $sale->receivables()->whereIn('status', ['open', 'overdue'])->delete();
    }

    /**
     * Check if receivables need to be synced
     *
     * @param Sale $sale The sale to check
     * @return bool True if receivables should be synced
     */
    public function shouldSyncReceivables(Sale $sale): bool
    {
        return $sale->wasRecentlyCreated
            || $sale->wasChanged([
                'sale_date',
                'installments',
                'payment_method',
                'custom_terms',
                'grand_total',
                'status'
            ]);
    }
}
