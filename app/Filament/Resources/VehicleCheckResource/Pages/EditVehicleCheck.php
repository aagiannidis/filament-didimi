<?php

namespace App\Filament\Resources\VehicleCheckResource\Pages;

use App\Filament\Resources\VehicleCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleCheck extends EditRecord
{
    protected static string $resource = VehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
