<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleFaultTemplateResource\Pages;
use App\Filament\Resources\VehicleFaultTemplateResource\RelationManagers;
use App\Models\VehicleFaultTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehicleFaultTemplateResource extends Resource
{
    protected static ?string $model = VehicleFaultTemplate::class;

    protected static ?string $navigationGroup = 'Vehicle Management';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_gr')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('precautions')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('precautions_gr')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('priority')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('priority'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleFaultTemplates::route('/'),
            'create' => Pages\CreateVehicleFaultTemplate::route('/create'),
            'view' => Pages\ViewVehicleFaultTemplate::route('/{record}'),
            'edit' => Pages\EditVehicleFaultTemplate::route('/{record}/edit'),
        ];
    }
}
