<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use App\Filament\Resources\RefuelingOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefuelingOrder extends EditRecord
{
    protected static string $resource = RefuelingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
