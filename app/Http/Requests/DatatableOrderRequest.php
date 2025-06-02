<?php

namespace App\Http\Requests;

use App\Http\Requests\JsonResponceErrors;

class DatatableOrderRequest extends JsonResponceErrors
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
            'datas.*.id' => 'required|integer',
            'datas.*.newOrder' => 'required|integer',
            'model' => 'required|string',
        ];
    }
}
