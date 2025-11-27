<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'syncro_subdomain' => 'required|string|max:255',
            'syncro_domain' => 'required|string|max:255',
            'syncro_api_key' => 'required|string|max:255',
            'eset_base_url' => 'required|string|max:255',
            'eset_username' => 'required|string|max:255',
            'eset_password' => 'required|string|max:255',
        ];
    }
}
