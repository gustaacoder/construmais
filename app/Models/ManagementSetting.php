<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementSetting extends Model
{
    protected $fillable = [
        'expense_forecast',
        'reference_period',
        'credit_card_default_terms',
        'pix_debit_default_terms',
        'safety_stock_days',
    ];
}
