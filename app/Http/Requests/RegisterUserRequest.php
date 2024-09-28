<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'mobile' => 'nullable|string|max:255|unique:users,telephone',
            'telephone' => 'nullable|integer|min:1',
            'isVerified' => 'nullable|boolean',
            'dob' => 'nullable|date_format:Y-m-d', // Ensure date is in 'Y-m-d' format
            'username' => 'nullable|string|max:255|unique:users,username',
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'number_verify_at' => 'nullable|date',
            'create_pin' => 'nullable|string|max:255',
            'status' => 'nullable|integer|in:0,1',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.unique' => 'The email has already been taken.',
            'username.unique' => 'The username has already been taken.',
            'password.min' => 'Password must be at least 8 characters.',
            'dob.date_format' => 'Date of birth must be in the format YYYY-MM-DD.',
            'gender.in' => 'Gender must be either male, female, or other.',
            'status.in' => 'Status must be either 0 (inactive) or 1 (active).',
        ];
    }
}
