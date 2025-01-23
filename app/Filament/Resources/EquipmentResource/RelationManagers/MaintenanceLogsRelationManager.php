<?php

namespace App\Filament\Resources\EquipmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'MaintenanceLogs';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
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
}
