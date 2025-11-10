<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

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

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = [];

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

    /**
     * Check if there's sufficient stock for a quantity
     *
     * @param  int  $quantity  Required quantity
     */
    public function hasSufficientStock(int $quantity): bool
    {
        return $this->stock_on_hand >= $quantity;
    }

    /**
     * Scope to filter products with low stock
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock_on_hand', '<=', 'min_stock');
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
