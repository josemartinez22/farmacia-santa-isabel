<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'min:3', 'max:50', 'unique:products,name,' . Request::get('id')],
            'barcode' => ['sometimes'],
            'cost' => ['sometimes', 'decimal:2'],
            'price' => ['sometimes', 'decimal:2'],
            'stock' => ['sometimes', 'integer'],
            'alert' => ['sometimes', 'integer'],
            'status' => ['sometimes', 'integer'],
            'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
            'category_id' => ['sometimes', 'numeric']
        ];
    }
}
