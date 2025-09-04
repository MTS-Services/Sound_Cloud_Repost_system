<?php

namespace App\Http\Requests\Admin\PackageManagement;

use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'tag' => ['nullable', Rule::in(array_keys(Plan::getTagList()))],
            'features' => ['required', 'array', 'min:10'],
            'features.*' => ['required', 'exists:features,id'],

            'feature_values' => ['required', 'array'],
            'feature_values.*' => ['required', 'max:255'],

        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [

        ];
    }
    protected function update(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [
            'features.*.required' => 'Please select at least one feature.',
            'feature_values.*.required' => 'Please enter all feature values.',
            'feature_values.*.max' => 'Feature value may not be greater than 255 characters.',
            'features.*.exists' => 'Feature does not exist.',
            'features.min' => 'Please select all features.',

        ];
    }
}
