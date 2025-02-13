<?php

namespace App\Filament\Resources\VehicleLicenceResource\Pages;

use App\Filament\Resources\VehicleLicenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleLicences extends ListRecords
{
    protected static string $resource = VehicleLicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
