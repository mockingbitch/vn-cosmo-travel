<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('thumbnail_media_id') === '') {
            $this->merge(['thumbnail_media_id' => null]);
        }
        $this->merge([
            'thumbnail' => $this->input('thumbnail') !== null
                ? trim((string) $this->input('thumbnail'))
                : '',
        ]);
        if (! $this->has('services')) {
            $this->merge(['services' => []]);
        }
        if (! $this->has('amenities')) {
            $this->merge(['amenities' => []]);
        }
        if (! $this->has('gallery')) {
            $this->merge(['gallery' => []]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'services' => ['present', 'array'],
            'services.*' => ['string', Rule::in(config('tour_catalog.services', []))],
            'amenities' => ['present', 'array'],
            'amenities.*' => ['string', Rule::in(config('tour_catalog.amenities', []))],
            'price' => ['required', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'thumbnail_media_id' => ['nullable', 'integer', 'exists:media,id'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string', 'max:10000'],
            'gallery' => ['present', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
