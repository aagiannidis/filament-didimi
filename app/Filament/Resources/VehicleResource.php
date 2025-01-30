<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleModel;
use Forms\Components\Select;
use Filament\Resources\Resource;
use App\Models\VehicleManufacturer;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleResource\RelationManagers;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'mdi-truck-outline';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('license_plate')
                    ->label('License plate')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('vehicle_identification_no')
                    ->label('VIN (Vehicle Identification Number)')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('engine_serial_no')
                    ->label('Engine serial number')
                    ->required()
                    ->maxLength(50),
                Forms\Components\TextInput::make('chassis_serial_no')
                    ->label('Chassis serial number')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('vehicle_manufacturer_id')
                    // ->options(
                    //     VehicleManufacturer::all()->pluck('name', 'id')->toArray()
                    // )
                    ->live()
                    ->relationship(name: 'manufacturer', titleAttribute: 'name')
                    ->preload()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('vehicle_model_id', -1);
                    })
                    ->required(),
                Forms\Components\Select::make('vehicle_model_id')
                    ->label('Model')
                    ->options(function (Get $get) {
                        $selectedManufId = $get('vehicle_manufacturer_id');
                        if ($selectedManufId) {
                            return VehicleModel::where('vehicle_manufacturer_id', $selectedManufId)->pluck('model', 'id')->toArray();
                        }
                    })
                    ->required(),
                Forms\Components\DatePicker::make('manufacture_date')
                    ->label('Date of manufacture')
                    ->required(),
                Forms\Components\TextInput::make('color')
                    ->required()
                    ->maxLength(50),
                Forms\Components\Select::make('vehicle_type')
                    ->options(self::$model::VEHICLE_TYPES)
                    ->required(),
                Forms\Components\Select::make('fuel_type')
                    ->label('Type of fuel')
                    ->options(self::$model::FUEL_TYPES)
                    ->required(),
                Forms\Components\Select::make('emission_standard')
                    ->options(self::$model::EMISSION_STANDARDS)
                    ->required(),
                Forms\Components\TextInput::make('weight')
                    ->label('Weight (kg)')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('seats')
                    ->label('Number of seats')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('license_plate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_identification_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('engine_serial_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('chassis_serial_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manufacturer.name')
                    ->label('Manufacturer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model.model')
                    ->label('Model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manufacture_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fuel_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('emission_standard')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seats')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
