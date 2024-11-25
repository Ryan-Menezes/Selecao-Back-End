<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'string', 'max:191', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6', 'max:191'],
        ];
    }
}
