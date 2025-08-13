<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;

class UpdateGoogleSheet extends Command
{
    protected $signature = 'sheet:update';
    protected $description = 'Обновить данные в Google Sheets из базы';

    private GoogleSheetService $sheetService;

    public function __construct(GoogleSheetService $sheetService)
    {
        parent::__construct();
        $this->sheetService = $sheetService;
    }

    public function handle()
    {
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = env('GOOGLE_SHEET_RANGE');

        $this->info("Обновляем таблицу $spreadsheetId, диапазон $range");

        $this->sheetService->updateSheet($spreadsheetId, $range);

        $this->info("Обновление завершено.");

        return 0;
    }
}
