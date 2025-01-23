<?php

namespace App\Filament\Resources;

use App\Models\Equipment;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EquipmentResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EquipmentResource\RelationManagers\AssignmentLogsRelationManager;
use App\Filament\Resources\EquipmentResource\RelationManagers\MaintenanceLogsRelationManager;

class EquipmentResource extends Resource
{
    protected static ?string $model = Equipment::class;
    protected static ?string $navigationGroup = 'Equipment Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'Laptop' => 'Laptop',
                        'Desktop' => 'Desktop',
                        'Monitor' => 'Monitor',
                        'Printer' => 'Printer',
                        'Phone' => 'Phone',
                        'Tablet' => 'Tablet',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('serial_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable(),
                Forms\Components\DateTimePicker::make('assigned_date'),
                Forms\Components\Select::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'MAINTENANCE' => 'Maintenance',
                        'RETIRED' => 'Retired',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('manufacturer')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('purchase_date'),
                Forms\Components\DatePicker::make('warranty_expiry'),
                Forms\Components\TextInput::make('purchase_cost')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Textarea::make('notes'),
                Forms\Components\KeyValue::make('specifications'),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable(),
                Forms\Components\Select::make('location_id')
                    ->relationship('location', 'name')
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'MAINTENANCE',
                        'danger' => 'RETIRED',
                    ]),
                Tables\Columns\TextColumn::make('department.name')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'MAINTENANCE' => 'Maintenance',
                        'RETIRED' => 'Retired',
                    ]),
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /*
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('serial_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('assigned_to')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('assigned_date'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('manufacturer')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('purchase_date'),
                Forms\Components\DatePicker::make('warranty_expiry'),
                Forms\Components\TextInput::make('purchase_cost')
                    ->numeric(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('specifications'),
                Forms\Components\TextInput::make('department_id')
                    ->numeric(),
                Forms\Components\TextInput::make('location_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('assigned_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('warranty_expiry')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_cost')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_id')
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
                Tables\Columns\TextColumn::make('deleted_at')
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
    */

    public static function getRelations(): array
    {
        return [
            MaintenanceLogsRelationManager::class,
            AssignmentLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipment::route('/'),
            'create' => Pages\CreateEquipment::route('/create'),
            'view' => Pages\ViewEquipment::route('/{record}'),
            'edit' => Pages\EditEquipment::route('/{record}/edit'),
        ];
    }
}
