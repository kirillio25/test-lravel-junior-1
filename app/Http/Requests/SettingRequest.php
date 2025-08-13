<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'google_sheet_url' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'google_sheet_url.required' => 'Введите ссылку на Google Sheet',
            'google_sheet_url.url' => 'Укажите корректный URL',
        ];
    }
}
