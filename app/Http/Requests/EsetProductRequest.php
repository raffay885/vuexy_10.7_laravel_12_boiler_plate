<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EsetProductRequest extends FormRequest
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
            'syncro_product_id' => 'required|exists:syncro_products,id',
            'eset_product_code' => 'required|string|max:255|unique:eset_products,eset_product_code',
            'eset_product_name' => 'required|string|max:255|unique:eset_products,eset_product_name',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['eset_product_code'] = 'required|string|max:255|unique:eset_products,eset_product_code,' . $this->eset_product;
            $rules['eset_product_name'] = 'required|string|max:255|unique:eset_products,eset_product_name,' . $this->eset_product;
        }

        return $rules;
    }
}
