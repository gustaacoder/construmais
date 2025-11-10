<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ManagementDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Dashboard de Gerenciamento';
    protected static ?string $navigationGroup = 'Gerenciamento';
    protected static string $view = 'filament.pages.management-dashboard';

    protected static ?int $navigationSort = 1;

    public function getTitle(): string|Htmlable
    {
        return (__('Dashboard de Gerenciamento'));
    }
}
