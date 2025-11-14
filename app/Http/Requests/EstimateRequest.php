<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstimateRequest extends FormRequest
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
        $rules = [
           'number' => 'required|string|max:255',
            'date' => 'required|date',
            'customer_id' => 'required|exists:users,id',
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'required',
            'note' => 'required|string|max:255',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['number'] = 'required|string|max:255|unique:estimates,number,' . $this->estimate;
        }

        return $rules;
    }
}
