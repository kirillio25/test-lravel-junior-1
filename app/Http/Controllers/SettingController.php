<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $url = Setting::get('google_sheet_url', '');
        $id  = Setting::get('google_sheet_id', '');
        return view('settings.edit', compact('url', 'id'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'google_sheet_url' => 'required|url'
        ]);

        $url = $data['google_sheet_url'];
        $id = $this->extractSheetId($url);

        if (!$id) {
            return redirect()->back()->withErrors(['google_sheet_url' => 'Не удалось извлечь ID из URL. Убедитесь, что ссылка выглядит как https://docs.google.com/spreadsheets/d/<ID>/...']);
        }

        Setting::set('google_sheet_url', $url);
        Setting::set('google_sheet_id', $id);

        return redirect()->route('settings.edit')->with('success', 'Google Sheet сохранён');
    }

    private function extractSheetId(string $url): ?string
    {
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
