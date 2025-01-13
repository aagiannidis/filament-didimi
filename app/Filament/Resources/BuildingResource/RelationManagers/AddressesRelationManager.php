<?php

namespace App\Filament\Resources\BuildingResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('street_address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('is_correspondence'),                                        
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                \Filament\Tables\Columns\CheckboxColumn::make('is_correspondence')
                    ->label('Διευθ.Επικοινωνίας')
                    ->disabled(),
                Tables\Columns\TextColumn::make('street_address'),
                Tables\Columns\TextColumn::make('street_number'),                    
                Tables\Columns\TextColumn::make('postal_code'),                                        
                Tables\Columns\TextColumn::make('additional_info'),
                
                    // ->relationship(name:'addresses',titleAttribute:'is_correspondence')
                    // //->hidden(fn (string $operation):bool=>$operation==='view')
                    // ->required(),
                    // ->relationship('addresses')
                    // ->schema([
                    //     Tables\Columns\TextColumn::make('is_correspondence')
                    //         ->label('License Registration')                            
                    // ])
                // Forms\Components\Section::make()
                //     ->relationship('vehicle')
                //     ->schema([
                //         Forms\Components\TextInput::make('license_plate')
                //             ->label('License Registration')                            
                //     ])
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
