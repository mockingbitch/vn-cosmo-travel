<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturedToursRequest extends FormRequest
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
        return [
            'featured_tours' => ['required', 'array'],
            'featured_tours.*.tour_id' => ['required', 'integer', 'exists:tours,id'],
            'featured_tours.*.featured_sort' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }
}
