<?php

namespace App\Filament\Resources\VehicleLicenceResource\Pages;

use App\Filament\Resources\VehicleLicenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Livewire\Attributes\On;

class ViewVehicleLicence extends ViewRecord
{
    protected static string $resource = VehicleLicenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    #[On('newUploadedDocument')]
    public function refresh(): void
    {
    }
}
