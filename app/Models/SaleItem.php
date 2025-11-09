<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'line_total',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function computeLineTotal(): float
    {
        $gross = (int) $this->quantity * (float) $this->unit_price;

        return round($gross - (float) ($this->discount ?? 0), 2);
    }

    protected static function booted(): void
    {
        static::saving(function (SaleItem $item){
            if (empty($item->line_total)) {
                $item->line_total = $item->computeLineTotal();
            }
        });

        static::saved(function (SaleItem $item){
            $item->sale?->recalcTotals();
        });

        static::deleted(function (SaleItem $item){
            $item->sale?->recalcTotals();
        });
    }
}
