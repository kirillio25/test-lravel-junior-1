<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\ItemRequest;
use App\Services\ItemService;

class ItemController extends Controller
{
    private ItemService $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = $this->itemService->getPaginated(20);
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(ItemRequest $request)
    {
        $this->itemService->create($request->validated());

        return redirect()->route('items.index')->with('success', 'Элемент добавлен');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(ItemRequest $request, Item $item)
    {
        $this->itemService->update($item, $request->validated());

        return redirect()->route('items.index')->with('success', 'Элемент обновлен');
    }

    public function destroy(Item $item)
    {
        $this->itemService->delete($item);

        return redirect()->route('items.index')->with('success', 'Элемент удален');
    }

    public function generate()
    {
        $this->itemService->generate(1000);

        return redirect()->back()->with('success', '1000 данных добавлены');
    }

    public function clear()
    {
        $this->itemService->clear();

        return redirect()->back()->with('success', 'Все удалено');
    }
}
