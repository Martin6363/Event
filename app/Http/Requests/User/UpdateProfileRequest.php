<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'specialization' => ['nullable', 'string'],
            'gender'         => ['nullable', 'in:male,female,other'],
            'phone'          => ['required', 'string', 'max:20'],
            'birthday'       => ['nullable', 'date'],
            'about'          => ['nullable', 'string', 'max:1000'],
        ];
    }
}
