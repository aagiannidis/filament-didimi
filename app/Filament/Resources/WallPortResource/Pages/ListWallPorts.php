<?php

namespace App\Filament\Resources\WallPortResource\Pages;

use App\Filament\Resources\WallPortResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWallPorts extends ListRecords
{
    protected static string $resource = WallPortResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
