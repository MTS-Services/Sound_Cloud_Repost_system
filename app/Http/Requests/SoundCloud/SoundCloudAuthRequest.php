<?php

namespace App\Http\Requests\SoundCloud;

use Illuminate\Foundation\Http\FormRequest;

class SoundCloudAuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|string',
            'state' => 'sometimes|string',
            'error' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Authorization code is required from SoundCloud.',
            'error.string' => 'An error occurred during SoundCloud authentication.',
        ];
    }
}
