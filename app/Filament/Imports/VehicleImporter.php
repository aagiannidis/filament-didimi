<?php

namespace App\Filament\Imports;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use App\Models\VehicleManufacturer;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class VehicleImporter extends Importer
{
    protected static ?string $model = Vehicle::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('license_plate')
                ->requiredMapping()
                ->rules(['unique:App\Models\Vehicle,license_plate','required', 'max:50']),
            ImportColumn::make('vehicle_identification_no')
                ->requiredMapping()
                ->rules(['unique:App\Models\Vehicle,vehicle_identification_no','required', 'max:50']),
            ImportColumn::make('engine_serial_no')
                ->requiredMapping()
                ->rules(['unique:App\Models\Vehicle,engine_serial_no','required', 'max:50']),
            ImportColumn::make('chassis_serial_no')
                ->requiredMapping()
                ->rules(['unique:App\Models\Vehicle,chassis_serial_no','required', 'max:50']),
            ImportColumn::make('manufacture_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('color')
                ->requiredMapping()
                ->rules(['required', 'max:50']),
            ImportColumn::make('vehicle_type')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('fuel_type')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('emission_standard')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('weight')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('seats')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('vehicle_manufacturer_id')
                ->requiredMapping()
                ->relationship('manufacturer', resolveUsing: function (string $state): ?VehicleManufacturer {
                    $s = VehicleManufacturer::where('name', $state)->orWhere('id', $state)->first();
                    return $s;
                }),
            ImportColumn::make('vehicle_model_id')
                ->requiredMapping()
                ->relationship('model', resolveUsing: function (string $state): ?VehicleModel {
                    $s = VehicleModel::where('model', $state)->orWhere('id', $state)->first();
                    return $s;
                }),
        ];
    }

    public function resolveRecord(): ?Vehicle
    {
        // return Vehicle::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Vehicle();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your vehicle import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
