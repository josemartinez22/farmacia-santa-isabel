<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'min:3', 'max:60'],
            'last_name' => ['sometimes', 'min:5', 'max:80'],
            'email' => ['sometimes', 'max:50', 'unique:users,email,' . Request::get('id')],
            'password' => ['nullable'],
            'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'image', 'max:2048'],
            'status' => ['sometimes', 'numeric'],
            'rol_name' => ['sometimes'],
        ];
    }
}
