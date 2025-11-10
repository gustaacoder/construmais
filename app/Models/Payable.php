<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payable extends Model
{
    protected $fillable = [
        'stock_entry_id',
        'document_no',
        'issue_date',
        'due_date',
        'amount',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function stockEntry(): BelongsTo
    {
        return $this->belongsTo(StockEntry::class);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function scopeOpen($q)
    {
        return $q->where('status', 'open');
    }

    public function scopeOverdue($q)
    {
        return $q->where('status', 'overdue');
    }
}
