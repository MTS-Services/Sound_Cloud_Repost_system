<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreditTransactionRequest extends FormRequest
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
            'receiver_id' => 'required|string|exists:users,urn',
            'sender_id' => 'nullable|string|exists:users,urn',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'repost_request_id' => 'nullable|exists:repost_requests,id',
            'transaction_type' => 'required|in:0,1,2,3,4,5',
            'amount' => 'required|numeric|min:0',
            'credits' => 'required|numeric|min:0',
            'balance_before' => 'required|numeric',
            'balance_after' => 'required|numeric',
            'description' => 'nullable|string',
            'metadata' => 'nullable|array',
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
