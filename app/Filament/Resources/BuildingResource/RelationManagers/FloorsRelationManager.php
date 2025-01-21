<?php

namespace App\Filament\Resources\BuildingResource\RelationManagers;

use App\Models\Floor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FloorsRelationManager extends RelationManager
{
    protected static string $relationship = 'floors';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $title = 'Building Floors';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Floor Details')
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Floor Number')
                            ->unique(ignoreRecord: true)
                            ->helperText('The floor number in the building'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->label('Floor Name')
                            ->placeholder('e.g., Ground Floor, First Floor'),

                        Forms\Components\FileUpload::make('floor_plan_url')
                            ->label('Floor Plan')
                            ->image()
                            ->directory('floor-plans')
                            ->maxSize(5120) // 5MB
                            ->helperText('Upload floor plan image (max 5MB)'),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->sortable()
                    ->label('Floor Number'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rooms_count')
                    ->counts('rooms')
                    ->label('Total Rooms')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_capacity')
                    ->label('Capacity')
                    ->getStateUsing(fn (Floor $record): int => $record->rooms->sum('capacity'))
                    ->sortable(),

                Tables\Columns\ImageColumn::make('floor_plan_url')
                    ->label('Floor Plan')
                    ->circular(false)
                    ->defaultImageUrl(url('/images/default-floor-plan.png')),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Add any specific filters if needed
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        // Ensure floor number is unique within the building
                        $data['building_id'] = $this->ownerRecord->id;
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this floor? This will also delete all rooms and associated data.'),
                Tables\Actions\ViewAction::make()
                    ->modalContentFooter(fn (Floor $record) => view('filament.resources.building.floors.view', [
                        'floor' => $record->load('rooms'),
                    ])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('number', 'asc')
            ->reorderable('number')
            ->poll('60s'); // Auto-refresh every 60 seconds
    }

    public function isReadOnly(): bool
    {
        // Implement your authorization logic here
        return false;
    }

    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()
    //         //->withCount('rooms')
    //         ->with('rooms');
    // }
}
