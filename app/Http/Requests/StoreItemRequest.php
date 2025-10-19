<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'type' => 'required|in:stok,peminjaman',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ];

        // If type is 'stok', require category, supplier and price
        if ($this->input('type') === 'stok') {
            $rules['category_id'] = 'required|exists:categories,id';
            $rules['supplier_id'] = 'required|exists:suppliers,id';
            $rules['harga'] = 'required|numeric|min:0|max:999999999';
        } else {
            // If type is 'peminjaman', make category, supplier and price optional
            $rules['category_id'] = 'nullable|exists:categories,id';
            $rules['supplier_id'] = 'nullable|exists:suppliers,id';
            $rules['harga'] = 'nullable|numeric|min:0|max:999999999';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'harga.max' => 'Harga tidak boleh lebih dari Rp 999.999.999',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
        ];
    }
}
