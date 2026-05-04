<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'translations' => ['required', 'array'],
        ];

        foreach (array_keys((array) config('locales.supported', [])) as $loc) {
            $rules["translations.{$loc}"] = ['required', 'array'];
            $rules["translations.{$loc}.title"] = ['required', 'string', 'max:255'];
            $rules["translations.{$loc}.content"] = ['required', 'string'];
        }

        return $rules;
    }
}
