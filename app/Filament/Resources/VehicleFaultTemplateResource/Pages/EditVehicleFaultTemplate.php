<?php

namespace App\Filament\Resources\VehicleFaultTemplateResource\Pages;

use App\Filament\Resources\VehicleFaultTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleFaultTemplate extends EditRecord
{
    protected static string $resource = VehicleFaultTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
