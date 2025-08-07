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

            'price_monthly' => ['nullable', 'numeric', 'min:0'],
            'price_monthly_yearly' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'tag' => ['nullable', Rule::in(array_keys(Plan::getTagList()))],
            'features' => ['nullable', 'array'],
            'features.*' => ['integer', 'exists:features,id'],

            'feature_values' => ['nullable', 'array'],
            'feature_values.*' => ['nullable', 'string', 'max:255'],
            'feature_category_ids' => ['nullable', 'array'],
            'feature_category_ids.*' => ['required', 'exists:feature_categories,id'],

        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'slug' => ['required', 'string', Rule::unique('plans', 'slug')],
        ];
    }
    protected function update(): array
    {
        return [
            'slug' => ['required', 'string', Rule::unique('plans', 'slug')->ignore(decrypt($this->route('plan')))],
        ];
    }
}
