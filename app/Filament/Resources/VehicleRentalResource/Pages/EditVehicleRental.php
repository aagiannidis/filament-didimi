<?php

namespace App\Filament\Resources\VehicleRentalResource\Pages;

use App\Filament\Resources\VehicleRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleRental extends EditRecord
{
    protected static string $resource = VehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
