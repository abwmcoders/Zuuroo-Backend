<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'mobile' => 'nullable|string|max:255',
            'telephone' => 'nullable|numeric',
            'dob' => 'nullable|date|date_format:Y-m-d',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user()->id,
            'gender' => 'nullable|string|in:male,female,other',
            'address' => 'nullable|string|max:255',
            'zipcode' => 'nullable|string|max:10',
            'country' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom messages for validation failures (optional).
     *
     * @return array
     */
    public function messages()
    {
        return [
            'dob.date_format' => 'The date of birth must be in the format Y-m-d.',
            'email.unique' => 'This email address is already in use.',
            'username.unique' => 'This username is already taken.',
        ];
    }
}
