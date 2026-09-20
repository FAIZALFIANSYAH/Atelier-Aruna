<?php
namespace App\Http\Requests\Transaction;
use Illuminate\Foundation\Http\FormRequest;
class CheckoutRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['payment_method' => ['required', 'in:bank_transfer,cod,e_wallet']]; }
}
