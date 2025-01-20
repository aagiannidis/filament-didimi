<?php

namespace App\Filament\Resources\VehicleCheckResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VehicleCheckResource;
use Parallax\FilamentComments\Actions\CommentsAction;

class EditVehicleCheck extends EditRecord
{
    protected static string $resource = VehicleCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CommentsAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
