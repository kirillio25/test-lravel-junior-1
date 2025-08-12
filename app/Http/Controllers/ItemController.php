<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleSheetService;

class ItemController extends Controller
{
    private GoogleSheetService $sheetService;

    public function __construct(GoogleSheetService $sheetService)
    {
        $this->sheetService = $sheetService;
    }

    public function index()
    {
        $items = Item::paginate(20);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited'
        ]);

        Item::create($request->all());

        $this->updateGoogleSheet();

        return redirect()->route('items.index')->with('success', 'Элемент добавлен');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Allowed,Prohibited'
        ]);

        $item->update($request->all());

        $this->updateGoogleSheet();

        return redirect()->route('items.index')->with('success', 'Элемент обновлен');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        $this->updateGoogleSheet();

        return redirect()->route('items.index')->with('success', 'Элемент удален');
    }

    public function generate()
    {
        $total = 1000;
        $half = intdiv($total, 2);

        for ($i = 0; $i < $half; $i++) {
            Item::create(['title' => 'Item A ' . $i, 'description' => 'Auto', 'status' => 'Allowed']);
        }
        for ($i = 0; $i < $half; $i++) {
            Item::create(['title' => 'Item P ' . $i, 'description' => 'Auto', 'status' => 'Prohibited']);
        }

        $this->updateGoogleSheet();

        return redirect()->back()->with('success', '1000 данных добавлены');
    }

    public function clear()
    {
        Item::truncate();

        $this->updateGoogleSheet();

        return redirect()->back()->with('success', 'Все удалено');
    }

    private function updateGoogleSheet()
    {
        try {
            $spreadsheetId = env('GOOGLE_SHEET_ID');
            $range = env('GOOGLE_SHEET_RANGE');
            $this->sheetService->updateSheet($spreadsheetId, $range);
            Log::info('Google Sheet успешно обновлена после изменений в элементах');
        } catch (\Exception $e) {
            Log::error('Ошибка обновления Google Sheet: ' . $e->getMessage());
        }
    }
}
