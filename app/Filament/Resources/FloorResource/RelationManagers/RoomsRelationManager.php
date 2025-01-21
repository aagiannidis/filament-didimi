<?php

namespace App\Filament\Resources\FloorResource\RelationManagers;

use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Floor Rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Details')
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->required()
                            ->maxLength(20)
                            ->label('Room Number')
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique identifier for the room'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->label('Room Name')
                            ->placeholder('e.g., Conference Room A'),

                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'OFFICE' => 'Office',
                                'MEETING_ROOM' => 'Meeting Room',
                                'COMMON_AREA' => 'Common Area',
                                'UTILITY' => 'Utility Room',
                                'SERVER_ROOM' => 'Server Room',
                            ])
                            ->default('OFFICE'),

                        Forms\Components\TextInput::make('capacity')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(4)
                            ->label('Room Capacity'),

                        Forms\Components\TextInput::make('area_sqm')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Area (m²)'),

                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'ACTIVE' => 'Active',
                                'MAINTENANCE' => 'Under Maintenance',
                                'INACTIVE' => 'Inactive',
                            ])
                            ->default('ACTIVE'),
                    ])
                    ->columns(2),

            //     Forms\Components\Section::make('Assets')
            //         ->schema([
            //             Forms\Components\Repeater::make('assets.hvac')
            //                 ->label('HVAC Assets')
            //                 ->schema([
            //                     Forms\Components\TextInput::make('asset_id')
            //                         ->required()
            //                         ->label('Asset ID'),
            //                 ])
            //                 ->collapsible()
            //                 ->defaultItems(0),

            //             Forms\Components\Repeater::make('assets.lighting')
            //                 ->label('Lighting Assets')
            //                 ->schema([
            //                     Forms\Components\TextInput::make('asset_id')
            //                         ->required()
            //                         ->label('Asset ID'),
            //                 ])
            //                 ->collapsible()
            //                 ->defaultItems(0),

            //             Forms\Components\Repeater::make('assets.network')
            //                 ->label('Network Assets')
            //                 ->schema([
            //                     Forms\Components\TextInput::make('asset_id')
            //                         ->required()
            //                         ->label('Asset ID'),
            //                 ])
            //                 ->collapsible()
            //                 ->defaultItems(0),

            //             Forms\Components\Repeater::make('assets.electrical')
            //                 ->label('Electrical Assets')
            //                 ->schema([
            //                     Forms\Components\TextInput::make('asset_id')
            //                         ->required()
            //                         ->label('Asset ID'),
            //                 ])
            //                 ->collapsible()
            //                 ->defaultItems(0),
            //         ])
            //         ->collapsible(),
             ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'OFFICE',
                        'success' => 'MEETING_ROOM',
                        'warning' => 'COMMON_AREA',
                        'danger' => 'UTILITY',
                        'info' => 'SERVER_ROOM',
                    ])
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', $state)),

                Tables\Columns\TextColumn::make('capacity')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('area_sqm')
                    ->label('Area (m²)')
                    ->sortable()
                    ->alignRight(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'ACTIVE',
                        'warning' => 'MAINTENANCE',
                        'danger' => 'INACTIVE',
                    ]),

                Tables\Columns\TextColumn::make('wall_ports_count')
                    ->counts('wallPorts')
                    ->label('Wall Ports')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'OFFICE' => 'Office',
                        'MEETING_ROOM' => 'Meeting Room',
                        'COMMON_AREA' => 'Common Area',
                        'UTILITY' => 'Utility Room',
                        'SERVER_ROOM' => 'Server Room',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'ACTIVE' => 'Active',
                        'MAINTENANCE' => 'Under Maintenance',
                        'INACTIVE' => 'Inactive',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['floor_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this room? This will also delete all associated wall ports and asset assignments.'),
                Tables\Actions\ViewAction::make()
                    ->modalContent(fn (Room $record) => view('filament.resources.building.rooms.view', [
                        'room' => $record->load('wallPorts'),
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('number', 'asc')
            ->reorderable('number')
            ->poll('60s');
    }

    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()
    //         //->withCount('wallPorts')
    //         ->with('wallPorts');
    // }
}
