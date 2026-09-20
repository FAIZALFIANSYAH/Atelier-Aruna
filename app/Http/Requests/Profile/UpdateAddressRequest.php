<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'address_recipient' => ['required', 'string', 'max:120'],
            'address_phone' => ['required', 'regex:/^(?:\+62|62|0)[0-9]{8,13}$/'],
            'address' => ['required', 'string', 'max:1000'],
            'address_city' => ['required', 'string', 'max:120'],
            'address_postal_code' => ['required', 'digits:5'],
            'address_province' => ['required', 'in:Jawa Tengah,Jawa Timur'],
            'address_district' => ['required', 'string', 'max:120'],
            'address_subdistrict' => ['required', 'string', 'max:120'],
            'address_village' => ['required', 'string', 'max:120'],
        ];
    }
}
