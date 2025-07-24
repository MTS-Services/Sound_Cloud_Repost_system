<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserCreditRequest extends FormRequest
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
            'sort_order' => 'nullable|integer|min:0',
            'user_urn' => 'required|exists:users,urn',
            'transaction_id' => 'required|exists:credit_transactions,id',
            'status' => 'required|in:0,1,2',
            'amount' => 'required|numeric|min:0',
            'credits' => 'required|numeric|min:0',
        ] + ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store(): array
    {
        return [
            //
        ];
    }
    protected function update(): array
    {
        return [
            //
        ];
    }
}
