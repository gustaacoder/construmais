<?php

namespace App\Filament\Resources\ManagementSettingResource\Pages;

use App\Filament\Resources\ManagementSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManagementSetting extends EditRecord
{
    protected static string $resource = ManagementSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
