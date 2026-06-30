<?php

namespace App\Http\Requests\Admin;

use App\Models\Tour;
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
            'services.*' => ['string', 'max:120'],
            'amenities' => ['present', 'array'],
            'amenities.*' => ['string', 'max:120'],
            'price' => ['required', 'integer', 'min:0'],
            'currency' => ['required', 'string', Rule::in(array_keys(Tour::CURRENCIES))],
            'thumbnail' => ['nullable', 'string', 'max:2048'],
            'thumbnail_media_id' => ['nullable', 'integer', 'exists:media,id'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string', 'max:10000'],
            'itinerary.*.schedule' => ['nullable', 'array'],
            'itinerary.*.schedule.*.time' => ['nullable', 'string', 'max:32'],
            'itinerary.*.schedule.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.schedule.*.description' => ['nullable', 'string', 'max:2000'],
            'gallery' => ['present', 'array'],
            'gallery.*' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', 'string', Rule::in([Tour::STATUS_ACTIVE, Tour::STATUS_DISABLED])],
        ];
    }
}
