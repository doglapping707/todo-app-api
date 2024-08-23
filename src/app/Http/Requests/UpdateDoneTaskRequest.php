<?php

namespace App\Http\Requests;

use App\Http\Requests\ApiRequest;

class UpdateDoneTaskRequest extends ApiRequest
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
            'is_done' => 'required|boolean'
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
            'is_done.required' => 'id_doneが空の状態です。',
            'is_done.boolean'  => 'is_doneは真偽値である必要があります。',
        ];
    }
}
