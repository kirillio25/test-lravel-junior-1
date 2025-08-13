<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;

class ItemService
{
    /** @var GoogleSheetService */
    private $sheetService;

    public function __construct(GoogleSheetService $sheetService)
    {
        $this->sheetService = $sheetService;
    }

    public function getPaginated(int $perPage = 20)
    {
        return Item::paginate($perPage);
    }

    public function create(array $data)
    {
        Item::create($data);
        $this->updateGoogleSheet();
    }

    public function update(Item $item, array $data)
    {
        $item->update($data);
        $this->updateGoogleSheet();
    }

    public function delete(Item $item)
    {
        $item->delete();
        $this->updateGoogleSheet();
    }

    public function generate(int $total)
    {
        $half = intdiv($total, 2);

        for ($i = 0; $i < $half; $i++) {
            Item::create([
                'title' => 'Item A ' . $i,
                'description' => 'Auto',
                'status' => 'Allowed',
            ]);
        }

        for ($i = 0; $i < $half; $i++) {
            Item::create([
                'title' => 'Item P ' . $i,
                'description' => 'Auto',
                'status' => 'Prohibited',
            ]);
        }

        $this->updateGoogleSheet();
    }

    public function clear()
    {
        Item::truncate();
        $this->updateGoogleSheet();
    }

    private function updateGoogleSheet()
    {
        try {
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            $range = env('GOOGLE_SHEET_RANGE');
            $this->sheetService->updateSheet($spreadsheetId, $range);

            Log::info('Google Sheet успешно обновлена');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления Google Sheet: ' . $e->getMessage());
        }
    }
}
