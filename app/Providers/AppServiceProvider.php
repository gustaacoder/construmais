<?php

namespace App\Providers;

use App\Models\Sale;
use App\Models\StockEntry;
use App\Observers\SaleObserver;
use App\Observers\StockEntryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sale::observe(SaleObserver::class);
        StockEntry::observe(StockEntryObserver::class);
    }
}
