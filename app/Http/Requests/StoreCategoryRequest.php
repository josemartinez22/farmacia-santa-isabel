<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:categories', 'min:3', 'max:60'],
            'description' => ['required', 'string', 'min:5', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,gif,png,jpeg', 'max:2048']
        ];
    }
}
