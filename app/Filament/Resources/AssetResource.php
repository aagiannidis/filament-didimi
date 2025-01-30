<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Asset;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Section;
use Forms\Components\Group;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AssetResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Filament\Resources\AssetResource\RelationManagers\VehicleChecksRelationManager;
use App\Filament\Resources\AssetResource\RelationManagers\VehicleRentalsRelationManager;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'mdi-garage';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('asset_reference')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('license_plate')
                    ->maxLength(50),

                Forms\Components\Section::make('Purchase Details')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_purchase'),
                        Forms\Components\TextInput::make('cost_of_purchase')
                            ->numeric(),
                        Forms\Components\TextInput::make('condition')
                            ->required(),
                    ])
                    ->columns(3),
                Forms\Components\Select::make('vehicle_id')
                    ->relationship(name: 'vehicle', titleAttribute: 'license_plate')
                    //->hidden(fn (string $operation):bool=>$operation==='view')
                    ->required(),
                Forms\Components\Section::make()
                    ->relationship('vehicle')
                    ->schema([
                        Forms\Components\TextInput::make('license_plate')
                            ->label('License Registration')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_purchase')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost_of_purchase')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition'),
                Tables\Columns\TextColumn::make('vehicle_id')
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
            VehicleChecksRelationManager::class,
            VehicleRentalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
}
