<?php

namespace App\Filament\Resources\VehicleCheckResource\Pages;

use App\Filament\Resources\VehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVehicleCheck extends ViewRecord
{
    protected static string $resource = VehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    
}
