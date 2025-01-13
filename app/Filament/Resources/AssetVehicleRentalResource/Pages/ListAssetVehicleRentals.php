<?php

namespace App\Filament\Resources\AssetVehicleRentalResource\Pages;

use App\Filament\Resources\AssetVehicleRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetVehicleRentals extends ListRecords
{
    protected static string $resource = AssetVehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
