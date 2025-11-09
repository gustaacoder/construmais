<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'sale_date',
        'customer_id',
        'payment_method',
        'custom_terms',
        'status',
        'subtotal',
        'discount_total',
        'surcharge_total',
        'grand_total',
        'installments',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'due_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function receivables(): HasMany
    {
        return $this->hasMany(Receivable::class);
    }

    public function recalcTotals(): void
    {
        $subtotal = (float) $this->items()->sum('line_total');
        if ($subtotal <= 0) {
            $subtotal = $this->items->sum(fn($it) => $it->quantity * (float) $it->unit_price - (float) $it->discount);
        }

        $discount = (float) ($this->discount_total ?? 0);
        $surcharge = (float) ($this->surcharge_total ?? 0);
        $grand = max(0, $subtotal - $discount + $surcharge);

        $this->forceFill([
            'subtotal'       => round($subtotal, 2),
            'discount_total' => round($discount, 2),
            'surcharge_total'=> round($surcharge, 2),
            'grand_total'    => round($grand, 2),
        ])->save();
    }

    public function scopeConfirmed($q) { return $q->where('status', 'confirmed'); }
}
