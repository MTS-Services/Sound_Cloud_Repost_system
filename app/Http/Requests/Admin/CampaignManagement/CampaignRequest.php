<?php

namespace App\Http\Requests\Admin\CampaignManagement;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
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
        $data = $this->all();
        return [
            'music_id'               => ['nullable', 'numeric'],
            'music_type'               => ['nullable', 'string'],
            'title'                  => ['required', 'string', 'max:255'],
            'description'            => ['nullable', 'string'],
            'credits_per_repost'     => ['required', 'numeric',],
            'total_credits_budget'         => ['required', 'numeric'],
            'start_date'             => ['nullable', 'date'],
            'auto_approve'           => ['required', 'boolean'],
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
