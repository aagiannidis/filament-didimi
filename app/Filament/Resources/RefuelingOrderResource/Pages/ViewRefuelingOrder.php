<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use App\Filament\Resources\RefuelingOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRefuelingOrder extends ViewRecord
{
    protected static string $resource = RefuelingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
