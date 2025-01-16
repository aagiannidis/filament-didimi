<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Select;
use App\Models\VehicleRental;
use Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Forms\Components\DatePicker;
use Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleRentalResource\Pages;
use Parallax\FilamentComments\Infolists\Components\CommentsEntry;
use App\Filament\Resources\VehicleRentalResource\RelationManagers;

class VehicleRentalResource extends Resource
{
    protected static ?string $model = VehicleRental::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([                
                Infolists\Components\TextEntry::make('rental_date'),
                Infolists\Components\TextEntry::make('return_date'),
                Infolists\Components\TextEntry::make('rental_cost'),
                Infolists\Components\TextEntry::make('rental_status'),                  
                Infolists\Components\TextEntry::make('asset.license_plate'),
                CommentsEntry::make('filament_comments'),
            ]);
    }

    public static function form(Form $form): Form
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
                    ->options(self::$model::RENTAL_STATUSES)
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
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
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
            'index' => Pages\ListVehicleRentals::route('/'),
            'create' => Pages\CreateVehicleRental::route('/create'),
            'view' => Pages\ViewVehicleRental::route('/{record}'),
            'edit' => Pages\EditVehicleRental::route('/{record}/edit'),
        ];
    }
}
