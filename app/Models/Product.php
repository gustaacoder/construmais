<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'unit',
        'category',
        'brand',
        'avg_cost',
        'sale_price',
        'min_stock',
    ];

    protected $appends = ['stock_on_hand'];

    public function stockEntries(): HasMany
    {
        return $this->hasMany(StockEntry::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getStockOnHandAttribute(): int
    {
        $in = (int) $this->stockEntries()->sum('quantity');
        $out = (int) $this->saleItems()->sum('quantity');

        return $in - $out;
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
