<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetVehicleRentalResource\Pages;
use App\Filament\Resources\AssetVehicleRentalResource\RelationManagers;
use App\Models\AssetVehicleRental;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetVehicleRentalResource extends Resource
{
    protected static ?string $model = AssetVehicleRental::class;

    protected static ?string $navigationIcon = 'mdi-car-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->relationship('asset', 'id')
                    ->required(),
                Forms\Components\Select::make('vehicle_rental_id')
                    ->relationship('vehicleRental', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicleRental.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssetVehicleRentals::route('/'),
            'create' => Pages\CreateAssetVehicleRental::route('/create'),
            'view' => Pages\ViewAssetVehicleRental::route('/{record}'),
            'edit' => Pages\EditAssetVehicleRental::route('/{record}/edit'),
        ];
    }
}
