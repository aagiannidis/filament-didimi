<?php

namespace App\Filament\Resources\RefuelingOrderResource\Pages;

use App\Filament\Resources\RefuelingOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefuelingOrders extends ListRecords
{
    protected static string $resource = RefuelingOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
