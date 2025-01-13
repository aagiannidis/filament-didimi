<?php

namespace App\Filament\Resources\AddressResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\AddressResource;
use CodeWithDennis\FactoryAction\FactoryAction;

class ListAddresses extends ListRecords
{
    protected static string $resource = AddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            FactoryAction::make()
                ->color('danger')
                // ->slideOver()
                ->icon('heroicon-o-wrench'),
            Actions\CreateAction::make(),
        ];
    }
}
