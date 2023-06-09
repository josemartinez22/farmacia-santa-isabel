<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max:60'],
            'last_name' => ['required', 'string', 'min:5', 'max:80'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'max:255', Rules\Password::defaults()],
            'image' => ['nullable', 'mimes:jpg,gif,png,jpeg', 'max:2048'],
            'status' => ['required', 'numeric', 'max:1'],
            'rol_name' => ['required']
        ];
    }
}
