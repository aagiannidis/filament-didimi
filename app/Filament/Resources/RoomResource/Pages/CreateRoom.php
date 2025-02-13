<?php

namespace App\Filament\Resources\RoomResource\Pages;

use Filament\Actions;
use App\Filament\Resources\RoomResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\FilamentHandleSecureDocuments;

class CreateRoom extends CreateRecord
{
    use FilamentHandleSecureDocuments;

    protected static string $resource = RoomResource::class;
}
