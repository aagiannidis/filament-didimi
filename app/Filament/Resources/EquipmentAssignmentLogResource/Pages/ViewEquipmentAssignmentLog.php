<?php

namespace App\Filament\Resources\EquipmentAssignmentLogResource\Pages;

use App\Filament\Resources\EquipmentAssignmentLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEquipmentAssignmentLog extends ViewRecord
{
    protected static string $resource = EquipmentAssignmentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
