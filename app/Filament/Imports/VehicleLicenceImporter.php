<?php

namespace App\Filament\Imports;

use App\Models\VehicleLicence;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class VehicleLicenceImporter extends Importer
{
    protected static ?string $model = VehicleLicence::class;

    public static function getColumns(): array
    {
        return [
            //
        ];
    }

    public function resolveRecord(): ?VehicleLicence
    {
        // return VehicleLicence::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new VehicleLicence();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your vehicle licence import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
