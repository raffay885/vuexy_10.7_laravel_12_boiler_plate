<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAssetRequest extends FormRequest
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
            'customer_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'asset_type_id' => 'required|string|max:255',
            'asset_serial' => 'required|string|max:255|unique:customer_assets,asset_serial',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['asset_serial'] = 'required|string|max:255|unique:customer_assets,asset_serial,' . $this->customerAsset;
        }

        return $rules;
    }
}
