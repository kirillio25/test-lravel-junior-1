<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;

class FetchItemsFromSheet extends Command
{
    protected $signature = 'fetch:items {count?}';
    protected $description = 'Вывести ID и комментарии из Google Sheets с progress bar';

    private GoogleSheetService $sheetService;

    public function __construct(GoogleSheetService $sheetService)
    {
        parent::__construct();
        $this->sheetService = $sheetService;
    }

    public function handle()
    {
        $countLimit = $this->argument('count');

        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = env('GOOGLE_SHEET_RANGE');

        $outputBuffer = [];

        $outputBuffer[] = "Получаем данные из таблицы: $spreadsheetId, диапазон: $range";

        $values = $this->sheetService->getSheetData($spreadsheetId, $range);

        if (empty($values) || count($values) <= 1) {
            $outputBuffer[] = 'Нет данных для вывода.';
            $this->line(implode("\n", $outputBuffer));
            return 0;
        }

        $header = $values[0];
        $headerLine = 'Заголовок: ' . implode(' | ', $header);
        $outputBuffer[] = $headerLine;
        $outputBuffer[] = str_repeat('─', mb_strlen($headerLine)); // аккуратная линия под заголовком

        $rows = array_slice($values, 1);

        $totalRows = count($rows);
        $limit = $countLimit ? min($countLimit, $totalRows) : $totalRows;

        for ($i = 0; $i < $limit; $i++) {
            $row = $rows[$i];

            $id = $row[0] ?? '(нет ID)';
            $title = $row[1] ?? '(нет Title)';
            $comment = trim($row[4] ?? '') ?: '(нет комментария)';

            $outputBuffer[] = "ID: $id, Title: $title, Комментарий: $comment";
        }

        $outputBuffer[] = 'Вывод завершён.';

        $this->line(implode("\n", $outputBuffer));

        return 0;
    }




}
