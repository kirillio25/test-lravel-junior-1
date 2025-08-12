<?php

use Illuminate\Support\Facades\Route;
use App\Services\GoogleSheetService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Symfony\Component\Console\Output\BufferedOutput;

Route::redirect('/', '/items');

Route::resource('items', App\Http\Controllers\ItemController::class);
Route::post('items/generate', [App\Http\Controllers\ItemController::class, 'generate'])->name('items.generate');
Route::post('items/clear', [App\Http\Controllers\ItemController::class, 'clear'])->name('items.clear');

Route::get('settings', [App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
Route::post('settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

Route::get('/test-storage', function () {
    $filename = 'test-file.txt';
    $content = 'Hello, storage!';

    Storage::put($filename, $content);

    $readContent = Storage::get($filename);

    return "Записано и прочитано из storage: " . $readContent;
});

Route::get('/fetch/{count?}', function ($count = 20) {
    $output = new BufferedOutput();

    Artisan::call('fetch:items', ['count' => $count], $output);

    return '<pre>' . e($output->fetch()) . '</pre>';
});


