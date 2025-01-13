<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleCheck;
use Forms\Components\Select;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleCheckResource\Pages;
use App\Filament\Resources\VehicleCheckResource\RelationManagers;

class VehicleCheckResource extends Resource
{
    protected static ?string $model = VehicleCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('check_date')
                    ->required(),
                Forms\Components\TextInput::make('check_type')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('check_result')
                    ->options(self::$model::CHECK_RESULTS)
                    ->required(),
                Forms\Components\Select::make('asset_id')
                    ->relationship('asset', 'license_plate')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('check_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('check_result'),
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
            'index' => Pages\ListVehicleChecks::route('/'),
            'create' => Pages\CreateVehicleCheck::route('/create'),
            'view' => Pages\ViewVehicleCheck::route('/{record}'),
            'edit' => Pages\EditVehicleCheck::route('/{record}/edit'),
        ];
    }
}
