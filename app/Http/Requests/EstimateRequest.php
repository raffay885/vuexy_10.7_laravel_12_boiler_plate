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
            'syncro_product_id' => 'required',
            'quantity' => 'required|integer|min:1',
            'customer_id' => 'required|exists:users,id',
            'note' => 'required|string|max:255',
        ];

        return $rules;
    }
}
