<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\CustomForms\SecureDocumentUploadForm;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('floor_id')
                        ->relationship('floor', 'name')
                        ->required()
                        ->searchable(),
                    TextInput::make('number')
                        ->required()
                        ->maxLength(20),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(100),
                    Select::make('type')
                        ->options([
                            'OFFICE' => 'Office',
                            'MEETING_ROOM' => 'Meeting Room',
                            'COMMON_AREA' => 'Common Area',
                            'UTILITY' => 'Utility',
                            'SERVER_ROOM' => 'Server Room',
                        ])
                        ->required(),
                    TextInput::make('capacity')
                        ->numeric()
                        ->required()
                        ->minValue(0),
                    TextInput::make('area_sqm')
                        ->numeric()
                        ->required()
                        ->label('Area (m²)'),
                    Select::make('status')
                        ->options([
                            'ACTIVE' => 'Active',
                            'MAINTENANCE' => 'Under Maintenance',
                            'INACTIVE' => 'Inactive',
                        ])
                        ->default('ACTIVE')
                        ->required(),
                ])->columns(2),
                ...SecureDocumentUploadForm::schema(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('floor.building.name')
                    ->sortable()
                    ->searchable()
                    ->label('Building'),
                TextColumn::make('floor.name')
                    ->sortable()
                    ->searchable()
                    ->label('Floor'),
                TextColumn::make('number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'OFFICE',
                        'success' => 'MEETING_ROOM',
                        'warning' => 'COMMON_AREA',
                        'danger' => 'UTILITY',
                        'secondary' => 'SERVER_ROOM',
                    ]),
                TextColumn::make('capacity')
                    ->sortable(),
                TextColumn::make('area_sqm')
                    ->label('Area (m²)')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'MAINTENANCE',
                        'danger' => 'INACTIVE',
                    ]),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'OFFICE' => 'Office',
                        'MEETING_ROOM' => 'Meeting Room',
                        'COMMON_AREA' => 'Common Area',
                        'UTILITY' => 'Utility',
                        'SERVER_ROOM' => 'Server Room',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'MAINTENANCE' => 'Under Maintenance',
                        'INACTIVE' => 'Inactive',
                    ]),
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->actions([
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
            \App\Filament\Resources\RoomResource\RelationManagers\WallPortsRelationManager::class,
            //RelationManagers\AssetsRelationManager::class,
            \App\Filament\Resources\RoomResource\RelationManagers\SecureDocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
            'view' => Pages\ViewRoom::route('/{record}'),
        ];
    }
}
