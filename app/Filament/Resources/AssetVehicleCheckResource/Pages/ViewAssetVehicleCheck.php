<?php

namespace App\Filament\Resources\AssetVehicleCheckResource\Pages;

use App\Filament\Resources\AssetVehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssetVehicleCheck extends ViewRecord
{
    protected static string $resource = AssetVehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
