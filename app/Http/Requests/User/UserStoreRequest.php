<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'is_admin' => ['nullable', 'boolean'],
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'max:191'],
        ];
    }
}
