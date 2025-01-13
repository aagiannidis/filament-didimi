<?php

namespace App\Filament\Resources\VehicleRentalResource\Pages;

use App\Filament\Resources\VehicleRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleRentals extends ListRecords
{
    protected static string $resource = VehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
