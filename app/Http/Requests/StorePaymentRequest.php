<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:pix,boleto,credit_card'],
            'amount' => ['required', 'numeric', 'min:1'],
            'customer.name' => ['required', 'string'],
            'customer.document' => ['required', 'string'],
            'payment_data' => ['required', 'array'],

            'payment_data.pix_key' => ['required_if:type,pix', 'string'],

            'payment_data.card_number' => ['required_if:type,credit_card', 'string'],
            'payment_data.card_holder' => ['required_if:type,credit_card', 'string'],
            'payment_data.expiration_date' => ['required_if:type,credit_card', 'string'],
            'payment_data.cvv' => ['required_if:type,credit_card', 'string'],

            'payment_data.cpf' => ['required_if:type,boleto', 'string'],
            'payment_data.address' => ['required_if:type,boleto', 'string'],
        ];
    }
}
