<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow anonymous submissions
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
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'start_at' => ['required','date'],
            'end_at' => ['nullable','date','after_or_equal:start_at'],
            'location' => ['nullable','string','max:255'],
            'organizer_name' => ['nullable','string','max:255'],
            'organizer_email' => ['nullable','email','max:255'],
            'contact_phone' => ['nullable','string','max:50'],
            'image' => ['nullable','image','max:5120'], // max 5MB
        ];
    }
}
