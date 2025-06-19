<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:admins,email'],
            'password' => ['required', 'string', 'min:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'These credentials are invalid.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password field is required.',
            'password.min' => 'Password must be at least :min characters.',
            'full_name.min' => 'Full name must be at least :min characters.',
            'full_name.max' => 'Full name must not exceed :max characters.',
        ];
    }
}
