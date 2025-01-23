<?php

namespace App\Filament\Resources\EquipmentMaintenanceLogResource\Pages;

use App\Filament\Resources\EquipmentMaintenanceLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipmentMaintenanceLog extends ViewRecord
{
    protected static string $resource = EquipmentMaintenanceLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
