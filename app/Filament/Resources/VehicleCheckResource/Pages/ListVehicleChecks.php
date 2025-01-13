<?php

namespace App\Filament\Resources\VehicleCheckResource\Pages;

use App\Filament\Resources\VehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleChecks extends ListRecords
{
    protected static string $resource = VehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
