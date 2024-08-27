<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class RegisterRequest extends ApiRequest
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
            'name'     => 'required|max:40',
            'email'    => 'required|max:255|email|unique:users,email',
            'password' => 'required|max:255'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'ユーザー名が入力されていません。',
            'name.max'          => 'ユーザー名が40文字を超えています。',
            'email.required'    => 'メールアドレスが入力されていません。',
            'email.max'         => 'メールアドレスが255文字を超えています。',
            'email.email'       => 'メールアドレスの形式が正しくありません。',
            'email.unique'      => 'このメールアドレスは既に使われています。',
            'password.required' => 'パスワードが入力されていません。',
            'password.max'      => 'パスワードが255文字を超えています。',
        ];
    }
}
