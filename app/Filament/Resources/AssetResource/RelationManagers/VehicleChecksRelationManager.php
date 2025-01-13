<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleCheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VehicleChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicleChecks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([                
                Forms\Components\DatePicker::make('check_date')
                    ->required(),
                Forms\Components\TextInput::make('check_type')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('check_result')
                    ->options(VehicleCheck::CHECK_RESULTS)
                    ->required()                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table            
            ->columns([
                Tables\Columns\TextColumn::make('check_date')
                    ->date()
                    ->sortable(),                    
                Tables\Columns\TextColumn::make('check_type'),                                        
                Tables\Columns\TextColumn::make('check_result'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
