<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['required', 'string', 'max:30'],
            'travel_date' => ['required', 'date', 'after_or_equal:today'],
            'people_count' => ['required', 'integer', 'min:1', 'max:50'],
            'note' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.name.required'),
            'email.required' => __('validation.email.required'),
            'email.email' => __('validation.email.email'),
            'phone.required' => __('validation.phone.required'),
            'travel_date.required' => __('validation.travel_date.required'),
            'travel_date.date' => __('validation.travel_date.date'),
            'travel_date.after_or_equal' => __('validation.travel_date.after_or_equal'),
            'people_count.required' => __('validation.people_count.required'),
            'people_count.integer' => __('validation.people_count.integer'),
            'people_count.min' => __('validation.people_count.min'),
            'people_count.max' => __('validation.people_count.max'),
            'note.max' => __('validation.note.max'),
        ];
    }
}
