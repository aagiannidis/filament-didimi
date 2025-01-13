<?php

namespace App\Filament\Resources\AssetVehicleRentalResource\Pages;

use App\Filament\Resources\AssetVehicleRentalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetVehicleRental extends EditRecord
{
    protected static string $resource = AssetVehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
