<?php

namespace App\Filament\Resources;

use App\Models\WallPort;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WallPortResource\Pages;


class WallPortResource extends Resource
{
    protected static ?string $model = WallPort::class;
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Select::make('room_id')
                        ->relationship('room', 'name')
                        ->required()
                        ->searchable(),
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
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.floor.building.name')
                    ->sortable()
                    ->searchable()
                    ->label('Building'),
                TextColumn::make('room.name')
                    ->sortable()
                    ->searchable()
                    ->label('Room'),
                TextColumn::make('port_number')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'DATA',
                        'success' => 'VOICE',
                    ]),
                BadgeColumn::make('location')
                    ->colors([
                        'primary',
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'INACTIVE',
                        'danger' => 'FAULTY',
                    ]),
                TextColumn::make('speed')
                    ->visible(fn ($state, $record, $column) => ($column->getTable()->getFilters()['type']->getState()['value'] ?? 'false') === 'DATA'),
                TextColumn::make('extension')
                    ->visible(fn ($state, $record, $column) => ($column->getTable()->getFilters()['type']->getState()['value'] ?? 'false') === 'VOICE'),
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('toggle')
                    ->label(fn (WallPort $record) => $record->status === 'ACTIVE' ? 'Deactivate' : 'Activate')
                    ->action(fn (WallPort $record) => $record->toggleStatus())
                    ->requiresConfirmation()
                    ->visible(fn (WallPort $record) => $record->status !== 'FAULTY'),
                Tables\Actions\Action::make('markFaulty')
                    ->label('Mark as Faulty')
                    ->action(fn (WallPort $record) => $record->markAsFaulty())
                    ->requiresConfirmation()
                    ->visible(fn (WallPort $record) => $record->status !== 'FAULTY')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListWallPorts::route('/'),
            'create' => Pages\CreateWallPort::route('/create'),
            'view' => Pages\ViewWallPort::route('/{record}'),
            'edit' => Pages\EditWallPort::route('/{record}/edit'),
        ];
    }
}
