<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetVehicleCheckResource\Pages;
use App\Filament\Resources\AssetVehicleCheckResource\RelationManagers;
use App\Models\AssetVehicleCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetVehicleCheckResource extends Resource
{
    protected static ?string $model = AssetVehicleCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('asset_id')
                    ->relationship('asset', 'id')
                    ->required(),
                Forms\Components\Select::make('vehicle_check_id')
                    ->relationship('vehicleCheck', 'id')
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
                Tables\Columns\TextColumn::make('vehicleCheck.id')
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
            'index' => Pages\ListAssetVehicleChecks::route('/'),
            'create' => Pages\CreateAssetVehicleCheck::route('/create'),
            'view' => Pages\ViewAssetVehicleCheck::route('/{record}'),
            'edit' => Pages\EditAssetVehicleCheck::route('/{record}/edit'),
        ];
    }
}
