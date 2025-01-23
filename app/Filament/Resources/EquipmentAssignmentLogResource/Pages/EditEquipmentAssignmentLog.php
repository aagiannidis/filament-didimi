<?php

namespace App\Filament\Resources\EquipmentAssignmentLogResource\Pages;

use App\Filament\Resources\EquipmentAssignmentLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEquipmentAssignmentLog extends EditRecord
{
    protected static string $resource = EquipmentAssignmentLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
