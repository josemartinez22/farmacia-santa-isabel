<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'min:3', 'max:60', 'unique:categories,name,' . Request::get('id')],
            'description' => ['sometimes', 'min:5', 'max:255'],
            'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'image', 'max:2048']
        ];
    }
}
