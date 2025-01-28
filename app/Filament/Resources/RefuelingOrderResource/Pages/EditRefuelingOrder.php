<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Traits\FilamentHandleSecureDocuments;
use App\Filament\Resources\RefuelingOrderResource;
use ZeeshanTariq\FilamentAttachmate\Core\HandleAttachments;

class EditRefuelingOrder extends EditRecord
{
    //use HandleAttachments;
    use FilamentHandleSecureDocuments;

    protected static string $resource = RefuelingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
