<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePixRequestTransaction extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'pix_key' => ['required', 'string', 'max:77'],
            'pix_key_type' => ['required', 'string', 'in:document,email,phone,random'],
            'amount' => ['required', 'numeric', 'gt:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'description' => ['nullable', 'string', 'max:140']
        ];
    }

    public function messages(): array
    {
        return [
            'amount.gt' => 'The transaction amount must be greater than zero.',
            'amount.regex' => 'The amount format must be valid currency (e.g., 150.50).',
            'pix_key_type.in' => 'The allowed key types are: document, email, phone, or random.',
        ];
    }
}