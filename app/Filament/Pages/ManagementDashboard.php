<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ManagementDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Management Dashboard';
    protected static ?string $navigationGroup = 'Management';
    protected static string $view = 'filament.pages.management-dashboard';

    protected static ?int $navigationSort = 1;
}
