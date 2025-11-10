<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
    ];

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
