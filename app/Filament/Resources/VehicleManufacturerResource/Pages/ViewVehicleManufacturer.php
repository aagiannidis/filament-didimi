<?php

namespace App\Filament\Resources\VehicleManufacturerResource\Pages;

use App\Filament\Resources\VehicleManufacturerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleManufacturer extends ViewRecord
{
    protected static string $resource = VehicleManufacturerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
