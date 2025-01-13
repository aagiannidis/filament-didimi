<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleRental;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VehicleRentalsRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicleRentals';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('rental_date')
                    ->required(),
                Forms\Components\DatePicker::make('return_date'),
                Forms\Components\TextInput::make('rental_cost')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('rental_status')
                    ->options(VehicleRental::RENTAL_STATUSES)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_id')
            ->columns([                
                Tables\Columns\TextColumn::make('rental_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rental_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rental_status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
