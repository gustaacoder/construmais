<?php

namespace App\Observers;

use App\Models\Payable;
use App\Models\StockEntry;
use Illuminate\Support\Carbon;

class StockEntryObserver
{
    public function saved(StockEntry $entry): void
    {
        $existing = $entry->payable;

        $amount = round(((float) $entry->purchase_price) * (int) $entry->quantity, 2);
        $due = Carbon::parse($entry->entry_date)->addDays((int) $entry->supplier_payment_terms);
        $doc = $entry->invoice_number ?: sprintf('PBL-%06d', $entry->id);

        if ($existing) {
            if ($existing->status === 'paid') {
                return;
            }
            $existing->update([
                'document_no' => $doc,
                'issue_date'  => $entry->entry_date,
                'due_date'    => $due,
                'amount'      => $amount,
            ]);
            return;
        }

        Payable::create([
            'stock_entry_id' => $entry->id,
            'document_no'    => $doc,
            'issue_date'     => $entry->entry_date,
            'due_date'       => $due,
            'amount'         => $amount,
            'status'         => 'open',
        ]);
    }

    public function deleted(StockEntry $entry): void
    {
        $entry->payable()->whereIn('status', ['open','overdue'])->delete();
    }
}
