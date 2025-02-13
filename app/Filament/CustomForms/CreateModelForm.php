<?php

namespace App\Filament\CustomForms;

use Filament\Forms\Form;
use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CreateModelForm extends FormsComponent
{
    public static function schema(): array
    {
        return [
                \Filament\Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(50),
                \Filament\Forms\Components\Select::make('vehicle_manufacturer_id')
                    ->relationship('vehicleManufacturer')
                    ->options(\App\Models\VehicleManufacturer::query()->pluck('name', 'id')->toArray())
                    ->required(),
            ];
    }
}
