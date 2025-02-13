<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\VehicleManufacturer;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleManufacturerResource\Pages;
use App\Filament\Resources\VehicleManufacturerResource\RelationManagers;
use App\Filament\Resources\VehicleManufacturerResource\RelationManagers\ModelsRelationManager;
use Guava\FilamentKnowledgeBase\Contracts\HasKnowledgeBase;
use Guava\FilamentKnowledgeBase\Facades\KnowledgeBase;

class VehicleManufacturerResource extends Resource implements HasKnowledgeBase
{
    protected static ?string $model = VehicleManufacturer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getDocumentation(): array
    {
        return [
            'prologue.getting-started',
            // 'users.authentication',
            // KnowledgeBase::model()::find('users.permissions'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(self::$model::TYPES)
                    ->required()
                    ->preload(),
                \Filament\Forms\Components\Section::make('Registered Models')
                    ->description('The list of registered models are shown below.')
                    ->schema([
                        \Filament\Forms\Components\View::make('custom.models')
                            ->label('Available Models')
                            ->viewData(
                                [
                                    'models' => $form->getRecord()->vehicleModels->pluck('model')->toArray()
                                ]
                            ),
                    ])
                    ->visible(fn(string $context): bool => $context === 'view')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type'),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options(self::$model::TYPES)
                    ->placeholder('Filter By Type'),
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
        if (request()->routeIs('filament.admin.resources.vehicle-manufacturers.view')) {
            return [];
        }

        return [
            ModelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleManufacturers::route('/'),
            'create' => Pages\CreateVehicleManufacturer::route('/create'),
            'view' => Pages\ViewVehicleManufacturer::route('/{record}'),
            'edit' => Pages\EditVehicleManufacturer::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        //return parent::getEloquentQuery()->withModels();
        return parent::getEloquentQuery()->with('vehicleModels');
    }

    // public static function getDocumentation(): array
    // {
    //     return [
    //         'prologue.getting-started',
    //     ];
    // }
}
