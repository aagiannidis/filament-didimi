<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Floor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FloorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FloorResource extends Resource
{
    protected static ?string $model = Floor::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('building_id')
                            ->relationship('building', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('number')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\FileUpload::make('floor_plan_url')
                            ->image()
                            ->directory('floor-plans'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('building.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('floor_plan_url'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('building')
                    ->relationship('building', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\FloorResource\RelationManagers\RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFloors::route('/'),
            'create' => Pages\CreateFloor::route('/create'),
            'edit' => Pages\EditFloor::route('/{record}/edit'),
            'view' => Pages\ViewFloor::route('/{record}'),
        ];
    }
}
