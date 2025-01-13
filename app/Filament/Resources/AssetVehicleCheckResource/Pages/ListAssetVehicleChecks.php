<?php

namespace App\Filament\Resources\AssetVehicleCheckResource\Pages;

use App\Filament\Resources\AssetVehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetVehicleChecks extends ListRecords
{
    protected static string $resource = AssetVehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
