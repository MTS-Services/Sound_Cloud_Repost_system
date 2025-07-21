<?php

namespace App\Http\Requests\Admin\PackageManagement;

use Illuminate\Foundation\Http\FormRequest;

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
            //
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'name' => 'required|string|unique:plans,name',
            'slug' => 'required|string|unique:plans,slug',
            'price_monthly' => 'required|numeric|min:0',
            'price_monthly_yearly' => 'required|numeric|min:0',
            'tag' => 'nullable|in:1,2',
            'notes' => 'nullable|string',
        ];
    }
    protected function update(): array
    {
        return [
            'name' => 'required|string|unique:plans,name,' . decrypt($this->route('plan')),
            'slug' => 'required|string|unique:plans,slug,' . decrypt($this->route('plan')),
            'price_monthly' => 'required|numeric|min:0',
            'price_monthly_yearly' => 'required|numeric|min:0',
            'tag' => 'nullable|in:1,2',
            'notes' => 'nullable|string',
        ];
    }
}
