<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Log;

class SyncItemsToGoogleSheet extends Command
{
    protected $signature = 'sync:items';
    protected $description = 'Синхронизировать Allowed items с Google Sheet';

    public function handle(GoogleSheetService $sheetService)
    {
        Log::info('Старт синхронизации...');

        $sheetService->syncWithDatabase(
            env('GOOGLE_SHEET_ID'),
            env('GOOGLE_SHEET_RANGE')
        );

        Log::info('Синхронизация завершена.');

        $this->info('Синхронизация завершена.');
    }
}
