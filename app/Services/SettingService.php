<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function getGoogleSheetSettings(): array
    {
        return [
            'url' => Setting::get('google_sheet_url', ''),
            'id'  => Setting::get('google_sheet_id', ''),
        ];
    }

    public function updateGoogleSheetUrl(string $url): bool
    {
        $id = $this->extractSheetId($url);

        if (!$id) {
            return false;
        }

        Setting::set('google_sheet_url', $url);
        Setting::set('google_sheet_id', $id);

        return true;
    }

    private function extractSheetId(string $url): ?string
    {
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $url, $m)) {
            return $m[1];
        }
        return null;
    }
}
