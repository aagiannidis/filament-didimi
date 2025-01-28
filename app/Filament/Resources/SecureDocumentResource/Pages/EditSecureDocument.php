<?php

namespace App\Filament\Resources\SecureDocumentResource\Pages;

use App\Filament\Resources\SecureDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSecureDocument extends EditRecord
{
    protected static string $resource = SecureDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
