<?php

namespace App\Filament\Exports;

use App\Models\Vehicle;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class VehicleExporter extends Exporter
{
    protected static ?string $model = Vehicle::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('license_plate'),
            ExportColumn::make('vehicle_identification_no'),
            ExportColumn::make('engine_serial_no'),
            ExportColumn::make('chassis_serial_no'),
            ExportColumn::make('manufacture_date'),
            ExportColumn::make('color'),
            ExportColumn::make('vehicle_type'),
            ExportColumn::make('fuel_type'),
            ExportColumn::make('emission_standard'),
            ExportColumn::make('weight'),
            ExportColumn::make('seats'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('vehicle_manufacturer_id'),
            ExportColumn::make('vehicle_model_id'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your vehicle export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
