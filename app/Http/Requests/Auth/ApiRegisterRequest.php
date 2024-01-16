<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ApiRegisterRequest extends FormRequest
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
            'role' => ['required'],
            'user_name' => ['required', 'max:47'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => 'required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => ['required'],
        ];
    }
    public function messages(): array
    {
        return [
            'role' => 'لطفا نقش خود را انتخاب کنید',
            'user_name' => 'نام کاربری الزامی',
            'email' => 'ایمیل تکراری',
            'password' => 'رمز صحصح نمی باشد',
            'password_confirmation' => 'تکرار رمز صحیح نمی باشد'
        ];
    }
}
