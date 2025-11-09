<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StockEntry extends Model
{
    protected $fillable = [
        'entry_date',
        'supplier_id',
        'product_id',
        'unit_cost',
        'purchase_price',
        'supplier_payment_terms',
        'invoice_number',
        'invoice_series',
        'batch',
        'expiration_date',
        'warehouse',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function payables(): HasOne
    {
        return $this->hasOne(Payable::class);
    }

    public function getTotalAmountAttribute(): float
    {
        return round($this->quantity * (float) $this->purchase_price, 2);
    }
}
