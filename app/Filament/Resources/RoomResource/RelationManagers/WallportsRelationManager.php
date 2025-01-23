<?php

namespace App\Filament\Resources\RoomResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class WallPortsRelationManager extends RelationManager
{
    protected static string $relationship = 'wallPorts';
    protected static ?string $recordTitleAttribute = 'port_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('port_number')
                    ->required()
                    ->maxLength(20),
                Select::make('type')
                    ->options([
                        'DATA' => 'Data',
                        'VOICE' => 'Voice',
                    ])
                    ->required(),
                Select::make('location')
                    ->options([
                        'NORTH' => 'North Wall',
                        'SOUTH' => 'South Wall',
                        'EAST' => 'East Wall',
                        'WEST' => 'West Wall',
                    ])
                    ->required(),
                Select::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                        'FAULTY' => 'Faulty',
                    ])
                    ->default('ACTIVE')
                    ->required(),
                TextInput::make('speed')
                    ->maxLength(20)
                    ->visible(fn (callable $get) => $get('type') === 'DATA'),
                TextInput::make('extension')
                    ->maxLength(20)
                    ->visible(fn (callable $get) => $get('type') === 'VOICE'),
                DateTimePicker::make('last_tested_date'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('port_number')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'primary' => 'DATA',
                        'success' => 'VOICE',
                    ]),
                TextColumn::make('location')
                    ->badge()
                    ->colors([
                        'primary',
                    ]),
                TextColumn::make('status')
                    ->Badge()
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'INACTIVE',
                        'danger' => 'FAULTY',
                    ]),
                TextColumn::make('speed')
                    ->visible(fn ($record) => (($record) && ($record->type === 'DATA'))),
                TextColumn::make('extension')
                    ->visible(fn ($record) => (($record) && ($record->type === 'VOICE'))),
                TextColumn::make('last_tested_date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'DATA' => 'Data',
                        'VOICE' => 'Voice',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'INACTIVE' => 'Inactive',
                        'FAULTY' => 'Faulty',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
