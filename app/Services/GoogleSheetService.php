<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Illuminate\Support\Facades\Log;
use App\Models\Item;
use Google\Service\Sheets\ClearValuesRequest;

class GoogleSheetService
{
    private Sheets $service;

    public function __construct()
    {
        Log::info('GoogleSheetService: инициализация клиента Google Sheets');

        $client = new Client();
        $client->setAuthConfig(env('GOOGLE_APPLICATION_CREDENTIALS'));
        $client->addScope(Sheets::SPREADSHEETS);

        $this->service = new Sheets($client);
    }

    public function clearSheet(string $spreadsheetId, string $range)
    {
        try {
            $clearRequest = new ClearValuesRequest();
            $this->service->spreadsheets_values->clear($spreadsheetId, $range, $clearRequest);
            Log::info("clearSheet: диапазон $range очищен.");
        } catch (\Exception $e) {
            Log::error("clearSheet: ошибка очистки диапазона $range - " . $e->getMessage());
        }
    }

    public function updateSheet(string $spreadsheetId, string $range)
    {
        Log::info("updateSheet: старт обновления таблицы, spreadsheetId=$spreadsheetId, range=$range");

        $this->clearSheet($spreadsheetId, $range);

        $items = Item::allowed()->get();
        Log::info('updateSheet: получено элементов с Allowed статусом: ' . $items->count());

        try {
            $currentValues = $this->service->spreadsheets_values->get($spreadsheetId, $range)->getValues() ?? [];
            Log::info('updateSheet: получено текущих значений из Google Sheets: ' . count($currentValues));
        } catch (\Exception $e) {
            Log::error('updateSheet: ошибка при получении данных из Google Sheets: ' . $e->getMessage());
            $currentValues = [];
        }

        $currentRowsById = [];
        foreach ($currentValues as $index => $row) {
            if ($index === 0) continue;
            $id = $row[0] ?? null;
            if ($id) {
                $currentRowsById[$id] = $row;
            }
        }

        $rows = [];
        $rows[] = ['ID', 'Title', 'Description', 'Status', 'Comments'];

        foreach ($items as $item) {
            $comment = $currentRowsById[$item->id][4] ?? '';
            $rows[] = [
                $item->id,
                $item->title,
                $item->description,
                $item->status,
                $comment
            ];
        }

        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $rows
        ]);
        $params = ['valueInputOption' => 'RAW'];

        try {
            $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            Log::info('updateSheet: данные успешно обновлены в Google Sheets');
        } catch (\Exception $e) {
            Log::error('updateSheet: ошибка при обновлении Google Sheets: ' . $e->getMessage());
        }
    }

    public function syncWithDatabase(string $spreadsheetId, string $range)
    {
        Log::info("syncWithDatabase: старт синхронизации с $spreadsheetId диапазон $range");

        $allowedItems = \App\Models\Item::allowed()->get();
        Log::info('syncWithDatabase: из базы получено Allowed элементов: ' . $allowedItems->count());

        try {
            $currentValues = $this->service->spreadsheets_values->get($spreadsheetId, $range)->getValues() ?? [];
            Log::info('syncWithDatabase: получено текущих строк из таблицы: ' . count($currentValues));
        } catch (\Exception $e) {
            Log::error('syncWithDatabase: ошибка получения данных из Google Sheets: ' . $e->getMessage());
            $currentValues = [];
        }

        $header = $currentValues[0] ?? ['ID', 'Title', 'Description', 'Status', 'Comments'];

        $currentRowsById = [];
        foreach (array_slice($currentValues, 1) as $row) {
            $id = $row[0] ?? null;
            if ($id) {
                $currentRowsById[$id] = $row;
            }
        }

        $rows = [];
        $rows[] = $header;

        foreach ($allowedItems as $item) {
            $comment = $currentRowsById[$item->id][4] ?? '';

            $rows[] = [
                $item->id,
                $item->title,
                $item->description,
                $item->status,
                $comment
            ];
        }

        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $rows
        ]);
        $params = ['valueInputOption' => 'RAW'];

        try {
            $this->service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            Log::info('syncWithDatabase: данные успешно обновлены в Google Sheets');
        } catch (\Exception $e) {
            Log::error('syncWithDatabase: ошибка при обновлении Google Sheets: ' . $e->getMessage());
        }
    }


    public function getSheetData($spreadsheetId, $range)
    {
        Log::info("getSheetData: получение данных из $spreadsheetId, range $range");

        try {
            $values = $this->service->spreadsheets_values->get($spreadsheetId, $range)->getValues();
            Log::info('getSheetData: получено строк: ' . count($values));
            return $values;
        } catch (\Exception $e) {
            Log::error('getSheetData: ошибка получения данных из Google Sheets: ' . $e->getMessage());
            return [];
        }
    }
}
