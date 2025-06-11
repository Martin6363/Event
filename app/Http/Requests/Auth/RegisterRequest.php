<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'          => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password'       => ['required', 'confirmed'], // Password::min(6)->mixedCase()->numbers(),
            'specialization' => ['nullable', 'string', 'max:255'],
            'gender'         => ['nullable', Rule::in(['male', 'female', 'other'])],
            'birthday'       => ['nullable', 'date', 'before:today'],
            'about'          => ['nullable', 'string', 'max:1000'],
        ];
    }
}
