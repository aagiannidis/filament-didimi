<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\EquipmentAssignmentLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EquipmentAssignmentLogResource\Pages;


class EquipmentAssignmentLogResource extends Resource
{
    protected static ?string $model = EquipmentAssignmentLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Equipment Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('equipment_id')
                    ->relationship('equipment', 'serial_number')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('assigned_by')
                    ->relationship('assignedByUser', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\DateTimePicker::make('assigned_date')
                    ->required(),
                Forms\Components\DateTimePicker::make('returned_date'),
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
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned To')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedByUser.name')
                    ->label('Assigned By')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('returned_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn ($query) => $query->whereNull('returned_date')),
                Tables\Filters\Filter::make('returned')
                    ->query(fn ($query) => $query->whereNotNull('returned_date')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEquipmentAssignmentLogs::route('/'),
            'create' => Pages\CreateEquipmentAssignmentLog::route('/create'),
            'edit' => Pages\EditEquipmentAssignmentLog::route('/{record}/edit'),
        ];
    }
}
