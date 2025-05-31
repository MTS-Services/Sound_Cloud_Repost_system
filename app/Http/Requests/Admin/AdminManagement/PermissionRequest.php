<?php

namespace App\Http\Requests\Admin\AdminManagement;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
            'prefix' => 'required|string',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }
    protected function store(): array
    {
        return [
            'name' => 'required|unique:permissions,name',
        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|unique:permissions,name,' . decrypt($this->route('permission')),
        ];
    }
}
