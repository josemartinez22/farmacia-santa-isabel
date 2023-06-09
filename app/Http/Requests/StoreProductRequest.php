<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:products', 'min:3', 'max:50'],
            'barcode' => ['nullable'],
            'cost' => ['required', 'decimal:2'],
            'price' => ['required', 'decimal:2'],
            'stock' => ['required', 'integer'],
            'alert' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
            'category_id' => ['required', 'numeric']
        ];
    }
}
