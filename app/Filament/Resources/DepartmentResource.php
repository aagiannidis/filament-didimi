<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Department;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\Department\RelationManagers;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Organization';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('manager_id')
                    ->relationship('manager', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('parent_department_id')
                    ->relationship('parentDepartment', 'name')
                    ->searchable(),
                Forms\Components\TextInput::make('budget')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manager.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('parentDepartment.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('manager')
                    ->relationship('manager', 'name'),
                Tables\Filters\TernaryFilter::make('status')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        if (!$record->canBeDeleted()) {
                            Notification::make()
                                ->title('Action Denied')
                                ->body('Filament: This department cannot be deleted because it has child departments.')
                                ->danger()
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // EmployeesRelationManager::class,
            // EquipmentRelationManager::class,
            // ChildDepartmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
