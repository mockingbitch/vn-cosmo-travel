<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeWhySettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'home_why' => ['nullable', 'array'],
            'home_why.vi' => ['nullable', 'array'],
            'home_why.vi.title' => ['nullable', 'string', 'max:255'],
            'home_why.vi.subtitle' => ['nullable', 'string', 'max:500'],
            'home_why.vi.items' => ['nullable', 'array'],
            'home_why.vi.items.*.title' => ['nullable', 'string', 'max:255'],
            'home_why.vi.items.*.desc' => ['nullable', 'string', 'max:2000'],
            'home_why.en' => ['nullable', 'array'],
            'home_why.en.title' => ['nullable', 'string', 'max:255'],
            'home_why.en.subtitle' => ['nullable', 'string', 'max:500'],
            'home_why.en.items' => ['nullable', 'array'],
            'home_why.en.items.*.title' => ['nullable', 'string', 'max:255'],
            'home_why.en.items.*.desc' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
