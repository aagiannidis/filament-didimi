<?php

namespace App\Filament\Resources;

use App\Models\EquipmentMaintenanceLog;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EquipmentMaintenanceLogResource\Pages;


class EquipmentMaintenanceLogResource extends Resource
{
    protected static ?string $model = EquipmentMaintenanceLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Equipment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'serial_number')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('maintenance_type')
                    ->options([
                        'Routine Check' => 'Routine Check',
                        'Repair' => 'Repair',
                        'Upgrade' => 'Upgrade',
                        'Cleaning' => 'Cleaning',
                        'Software Update' => 'Software Update',
                        'Hardware Replacement' => 'Hardware Replacement',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Select::make('performed_by')
                    ->relationship('performer', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\DateTimePicker::make('maintenance_date')
                    ->required(),
                Forms\Components\TextInput::make('cost')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Completed' => 'Completed',
                        'In Progress' => 'In Progress',
                        'Scheduled' => 'Scheduled',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('equipment.serial_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenance_type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('performer.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('maintenance_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->money()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Completed' => 'Completed',
                        'In Progress' => 'In Progress',
                        'Scheduled' => 'Scheduled',
                    ]),
                Tables\Filters\SelectFilter::make('maintenance_type')
                    ->options([
                        'Routine Check' => 'Routine Check',
                        'Repair' => 'Repair',
                        'Upgrade' => 'Upgrade',
                        'Cleaning' => 'Cleaning',
                        'Software Update' => 'Software Update',
                        'Hardware Replacement' => 'Hardware Replacement',
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentMaintenanceLogs::route('/'),
            'create' => Pages\CreateEquipmentMaintenanceLog::route('/create'),
            'edit' => Pages\EditEquipmentMaintenanceLog::route('/{record}/edit'),
        ];
    }
}
