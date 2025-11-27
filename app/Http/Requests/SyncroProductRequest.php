<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncroProductRequest extends FormRequest
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
            'syncro_product_id' => 'required|string|max:255|unique:syncro_products,syncro_product_id',
            'syncro_product_title' => 'required|string|max:255|unique:syncro_products,syncro_product_title',
            'billing_type' => 'required|in:monthly,annual',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['syncro_product_id'] = 'required|string|max:255|unique:syncro_products,syncro_product_id,' . $this->syncro_product;
            $rules['syncro_product_title'] = 'required|string|max:255|unique:syncro_products,syncro_product_title,' . $this->syncro_product;
        }

        return $rules;
    }
}
