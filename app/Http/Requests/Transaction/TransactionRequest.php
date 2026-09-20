<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',

            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'product wajib dipilih',

            'product_id.exists' => 'product tidak ditemukan',

            'quantity.required' => 'quantity wajib diisi',

            'quantity.integer' => 'quantity harus berupa angka',

            'quantity.min' => 'quantity minimal 1',
        ];
    }
}
