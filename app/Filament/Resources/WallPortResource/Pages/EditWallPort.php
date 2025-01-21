<?php

namespace App\Filament\Resources\WallPortResource\Pages;

use App\Filament\Resources\WallPortResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWallPort extends EditRecord
{
    protected static string $resource = WallPortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
