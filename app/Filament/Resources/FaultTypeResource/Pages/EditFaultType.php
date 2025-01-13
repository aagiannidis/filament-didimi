<?php

namespace App\Filament\Resources\FaultTypeResource\Pages;

use App\Filament\Resources\FaultTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaultType extends EditRecord
{
    protected static string $resource = FaultTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
