<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => 'required|unique:products,name|max:100',
            'category_id' => 'required',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'price' => 'required|min:0',
            'material' => 'nullable|string|max:100',
            'available_sizes' => 'nullable|string|max:100',
            'production_time' => 'nullable|string|max:100',
            'order_type' => 'required|in:ready_stock,pre_order',
            'artisan_name' => 'nullable|string|max:100',
            'is_customizable' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'product wajib di isi',
            'name.unique' => 'product sudah ada',
            'name.max' => 'product huruf 100',
            'category_id.required' => 'wajib di isi',
            'price.required' => 'wajib di isi',
            'price.min' => 'nomimal minimal Rp.0',
            'order_type.required' => 'Status pesanan wajib dipilih.',
            'order_type.in' => 'Status pesanan tidak valid.',
           


        ];
    }
}
