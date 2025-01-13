<?php

namespace App\Filament\Resources\FaultTypeResource\Pages;

use App\Filament\Resources\FaultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaultTypes extends ListRecords
{
    protected static string $resource = FaultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
