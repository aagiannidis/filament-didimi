<?php

namespace App\Filament\Imports;

use App\Models\VehicleManufacturer;
use App\Models\VehicleModel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Log;

class VehicleModelImporter extends Importer
{
    protected static ?string $model = VehicleModel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('model')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('vehicle_manufacturer_id')
                // ->requiredMapping()
                // ->relationship(resolveUsing: 'name')
                ->rules(['required'])
                ->relationship('vehicleManufacturer', resolveUsing: function (string $state): ?VehicleManufacturer {
                    Log::info('inside the resolveusing 2026');
                    Log::info($state);
                    $s = VehicleManufacturer::where('name', $state)->orWhere('id', $state)->first();
                    Log::info('found ' . $s->name);
                    return $s;
                })
        ];
    }

    public function resolveRecord(): ?VehicleModel
    {
        // return VehicleModel::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new VehicleModel();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your vehicle model import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
