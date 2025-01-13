<?php

namespace App\Filament\Resources\AssetVehicleRentalResource\Pages;

use App\Filament\Resources\AssetVehicleRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssetVehicleRental extends ViewRecord
{
    protected static string $resource = AssetVehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
