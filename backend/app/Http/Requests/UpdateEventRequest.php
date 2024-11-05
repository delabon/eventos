<?php

namespace App\Http\Requests;

use App\Enums\EventStatus;
use App\Rules\Base64Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(EventStatus::class)],
            'name' => ['required', 'max:255'],
            'description' => ['required', 'max:5000'],
            'country' => ['required', 'max:255'],
            'address' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'postal_code' => ['required', 'max:255'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
            'cover_image' => []
        ];
    }
}
