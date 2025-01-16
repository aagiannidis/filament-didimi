<?php

namespace App\Filament\Resources\VehicleRentalResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\VehicleRentalResource;
use Parallax\FilamentComments\Actions\CommentsAction;

class ViewVehicleRental extends ViewRecord
{
    protected static string $resource = VehicleRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            // CommentsAction::make(),
        ];
    }
}
