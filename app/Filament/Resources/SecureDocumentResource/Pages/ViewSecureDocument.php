<?php

namespace App\Filament\Resources\SecureDocumentResource\Pages;

use App\Filament\Resources\SecureDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSecureDocument extends ViewRecord
{
    protected static string $resource = SecureDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
