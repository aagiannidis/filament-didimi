<?php

namespace App\Filament\Resources;


use Filament\Forms;
use App\Models\Task;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;

use Filament\Forms\Components\Section;
use App\Filament\Resources\TaskResource\Pages;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Task Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignedTo', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\DatePicker::make('due_date')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options([
                                'TODO' => 'To Do',
                                'IN_PROGRESS' => 'In Progress',
                                'COMPLETED' => 'Completed',
                            ])
                            ->default('TODO'),
                        Forms\Components\Select::make('priority')
                            ->required()
                            ->options([
                                'LOW' => 'Low',
                                'MEDIUM' => 'Medium',
                                'HIGH' => 'High',
                            ])
                            ->default('MEDIUM'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'TODO',
                        'primary' => 'IN_PROGRESS',
                        'success' => 'COMPLETED',
                    ]),
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'success' => 'LOW',
                        'warning' => 'MEDIUM',
                        'danger' => 'HIGH',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('priority'),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedTo', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
