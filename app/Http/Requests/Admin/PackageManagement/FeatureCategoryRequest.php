<?php

namespace App\Http\Requests\Admin\PackageManagement;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FeatureCategoryRequest extends FormRequest
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
            //
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'name' => 'required|unique:feature_categories,name',
        ];
    }
    protected function update(): array
    {
        return [
            'name' => 'required|unique:feature_categories,name,' . decrypt($this->route('feature_category')),

        ];
    }
}
