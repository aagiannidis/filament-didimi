<?php

namespace App\Filament\Resources\RoomResource\Pages;

use Filament\Actions;
use App\Filament\Resources\RoomResource;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentHandleSecureDocuments;

class EditRoom extends EditRecord
{
    use FilamentHandleSecureDocuments;

    protected static string $resource = RoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
