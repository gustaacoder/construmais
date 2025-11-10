<?php

namespace App\Observers;

use App\Actions\CreateReceivablesAction;
use App\Models\Sale;

class SaleObserver
{
    public function __construct(
        private CreateReceivablesAction $createReceivablesAction
    ) {}

    public function saving(Sale $sale): void
    {
        $sale->recalcTotals();
    }

    public function saved(Sale $sale): void
    {
        if ($sale->status === 'confirmed') {
            $this->syncReceivables($sale);
        } else {
            $this->createReceivablesAction->deleteOpenReceivables($sale);
        }
    }

    public function deleted(Sale $sale): void
    {
        $this->createReceivablesAction->deleteOpenReceivables($sale);
    }

    protected function syncReceivables(Sale $sale): void
    {
        if (!$this->createReceivablesAction->shouldSyncReceivables($sale)) {
            return;
        }

        $this->createReceivablesAction->execute($sale);
    }
}
