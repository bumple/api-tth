<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:32'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Insert your user name',
            'email.required' => 'Insert your email address',
            'email.email' => 'Wrong type of email address',
            'email.unique' => 'Email already exist',
            'password.required' => 'Insert your password',
            'password.min' => 'Min characters is 8',
            'password.max' => 'Max characters is 32',
        ];
    }
}
