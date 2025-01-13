<?php

namespace App\Filament\Resources\FaultTypeResource\Pages;

use App\Filament\Resources\FaultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFaultType extends ViewRecord
{
    protected static string $resource = FaultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
