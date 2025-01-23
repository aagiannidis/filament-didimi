<?php

namespace App\Filament\Resources\EquipmentAssignmentLogResource\Pages;

use App\Filament\Resources\EquipmentAssignmentLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEquipmentAssignmentLogs extends ListRecords
{
    protected static string $resource = EquipmentAssignmentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
