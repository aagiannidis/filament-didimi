<?php

namespace App\Filament\Resources\AssetVehicleCheckResource\Pages;

use App\Filament\Resources\AssetVehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetVehicleCheck extends EditRecord
{
    protected static string $resource = AssetVehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
