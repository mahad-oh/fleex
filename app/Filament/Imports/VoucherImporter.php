<?php

namespace App\Filament\Imports;

use App\Models\Voucher;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class VoucherImporter extends Importer
{
    protected static ?string $model = Voucher::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('serial_num')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('code_encrypted')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Voucher
    {
        // return Voucher::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Voucher();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your voucher import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
