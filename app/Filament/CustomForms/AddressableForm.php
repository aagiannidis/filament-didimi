<?php

namespace App\Filament\CustomForms;

use Filament\Forms\FormsComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;


class AddressableForm extends FormsComponent
{
    public static function schema(): array
    {
        return [
                Group::make()
                    ->schema([
                        Textarea::make('street_address')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('street_number')
                            ->required()
                            ->numeric()
                            ->maxLength(6),
                        TextInput::make('unit_number')
                            ->numeric()
                            ->maxLength(5),
                        TextInput::make('postal_code')
                            ->required()
                            ->numeric()
                            ->maxLength(8),
                    ]),
                    Group::make()
                        ->schema([
                            Fieldset::make('Address Coordinates')
                                ->schema([
                                    TextInput::make('latitude')
                                    ->numeric(),
                                    TextInput::make('longitude')
                                        ->numeric(),
                                ]),
                            Textarea::make('additional_info')
                                ->rows(5)
                                ->columnSpanFull(),
                            Checkbox::make('is_correspondence')
                                ->label('Correspondence')
                                ->translateLabel(),

                        ]),



        ];
    }
}
