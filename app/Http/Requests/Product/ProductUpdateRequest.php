<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
        $productunique = $this->route('product');

        return [
            //
            'name' => ['required', 'max:100', Rule::unique('products', 'name')->ignore($productunique)],
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
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk tidak boleh lebih dari 100 karakter.',
            'name.unique' => 'Nama produk sudah digunakan, silakan pilih nama lain.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh kurang dari 0.'
        ];
    }
}
