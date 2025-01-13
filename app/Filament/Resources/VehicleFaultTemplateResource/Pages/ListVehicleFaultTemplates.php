<?php

namespace App\Filament\Resources\VehicleFaultTemplateResource\Pages;

use App\Filament\Resources\VehicleFaultTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleFaultTemplates extends ListRecords
{
    protected static string $resource = VehicleFaultTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
