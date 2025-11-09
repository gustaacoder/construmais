<?php

namespace App\Filament\Resources\ManagementSettingResource\Pages;

use App\Filament\Resources\ManagementSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManagementSettings extends ListRecords
{
    protected static string $resource = ManagementSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
