<?php

namespace App\Filament\Resources\EquipmentMaintenanceLogResource\Pages;

use App\Filament\Resources\EquipmentMaintenanceLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentMaintenanceLog extends EditRecord
{
    protected static string $resource = EquipmentMaintenanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
