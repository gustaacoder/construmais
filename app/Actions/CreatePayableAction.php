<?php

namespace App\Actions;

use App\Models\Payable;
use App\Models\StockEntry;
use Illuminate\Support\Carbon;

class CreatePayableAction
{
    /**
     * Create or update a payable for a stock entry
     *
     * @param StockEntry $entry The stock entry to create payable for
     * @return void
     */
    public function execute(StockEntry $entry): void
    {
        $existing = $entry->payable;

        $amount = $this->calculateAmount($entry);
        $dueDate = $this->calculateDueDate($entry);
        $documentNo = $this->generateDocumentNo($entry);

        if ($existing) {
            $this->updateExistingPayable($existing, $documentNo, $entry->entry_date, $dueDate, $amount);
            return;
        }

        $this->createNewPayable($entry, $documentNo, $dueDate, $amount);
    }

    /**
     * Delete open or overdue payables for a stock entry
     *
     * @param StockEntry $entry The stock entry to delete payables for
     * @return void
     */
    public function deleteOpenPayables(StockEntry $entry): void
    {
        $entry->payable()->whereIn('status', ['open', 'overdue'])->delete();
    }

    /**
     * Calculate the total amount for the payable
     *
     * @param StockEntry $entry The stock entry
     * @return float The calculated amount
     */
    private function calculateAmount(StockEntry $entry): float
    {
        return round(((float) $entry->purchase_price) * (int) $entry->quantity, 2);
    }

    /**
     * Calculate the due date for the payable
     *
     * @param StockEntry $entry The stock entry
     * @return Carbon The calculated due date
     */
    private function calculateDueDate(StockEntry $entry): Carbon
    {
        return Carbon::parse($entry->entry_date)->addDays((int) $entry->supplier_payment_terms);
    }

    /**
     * Generate document number for the payable
     *
     * @param StockEntry $entry The stock entry
     * @return string The document number
     */
    private function generateDocumentNo(StockEntry $entry): string
    {
        return $entry->invoice_number ?: sprintf('PBL-%06d', $entry->id);
    }

    /**
     * Update an existing payable if not paid
     *
     * @param Payable $payable The existing payable
     * @param string $documentNo The document number
     * @param mixed $issueDate The issue date
     * @param Carbon $dueDate The due date
     * @param float $amount The amount
     * @return void
     */
    private function updateExistingPayable(
        Payable $payable,
        string $documentNo,
        $issueDate,
        Carbon $dueDate,
        float $amount
    ): void {
        if ($payable->status === 'paid') {
            return;
        }

        $payable->update([
            'document_no' => $documentNo,
            'issue_date' => $issueDate,
            'due_date' => $dueDate,
            'amount' => $amount,
        ]);
    }

    /**
     * Create a new payable
     *
     * @param StockEntry $entry The stock entry
     * @param string $documentNo The document number
     * @param Carbon $dueDate The due date
     * @param float $amount The amount
     * @return void
     */
    private function createNewPayable(
        StockEntry $entry,
        string $documentNo,
        Carbon $dueDate,
        float $amount
    ): void {
        Payable::create([
            'stock_entry_id' => $entry->id,
            'document_no' => $documentNo,
            'issue_date' => $entry->entry_date,
            'due_date' => $dueDate,
            'amount' => $amount,
            'status' => 'open',
        ]);
    }
}
