<?php

namespace App\Http\Requests\Admin\Faq;

use App\Models\Faq;
use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'question' => 'required|string|max:255',
            'description' => 'required|string',
            'key' => 'required|unique:faqs,key|in:' . implode(',', array_keys(Faq::keyLists())),
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            'key' => 'required|integer|unique:faqs,key',
        ];
    }
    protected function update(): array
    {
        return [
            'key' => 'required|integer|unique:faqs,key,' . decrypt($this->route('faq')),
        ];
    }
}
