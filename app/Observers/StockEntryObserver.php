<?php

namespace App\Observers;

use App\Actions\CreatePayableAction;
use App\Models\StockEntry;

class StockEntryObserver
{
    public function __construct(
        private CreatePayableAction $createPayableAction
    ) {}

    public function saved(StockEntry $entry): void
    {
        $this->createPayableAction->execute($entry);
    }

    public function deleted(StockEntry $entry): void
    {
        $this->createPayableAction->deleteOpenPayables($entry);
    }
}
