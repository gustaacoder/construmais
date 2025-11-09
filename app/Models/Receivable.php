<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Receivable extends Model
{
    protected $fillable = [
        'sale_id',
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

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function scopeOpen($q) { return $q->where('status', 'open'); }
    public function scopeOverdue($q) { return $q->where('status', 'overdue'); }
}
