<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Services\SettingService;

class SettingController extends Controller
{
    private $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function edit()
    {
        $settings = $this->settingService->getGoogleSheetSettings();
        return view('settings.edit', [
            'url' => $settings['url'],
            'id'  => $settings['id'],
        ]);
    }

    public function update(SettingRequest $request)
    {
        $updated = $this->settingService->updateGoogleSheetUrl($request->validated()['google_sheet_url']);

        if (!$updated) {
            return redirect()->back()
                ->withErrors(['google_sheet_url' => 'Не удалось извлечь ID из URL. Убедитесь, что ссылка выглядит как https://docs.google.com/spreadsheets/d/<ID>/...']);
        }

        return redirect()->route('settings.edit')->with('success', 'Google Sheet сохранён');
    }
}
