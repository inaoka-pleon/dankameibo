<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:20'],
            'name_kana' => ['nullable', 'string'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'memo'      => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => 'ユーザー名',
            'name_kana'         => 'ユーザー名かな',
            'email'             => 'メールアドレス',
            'memo'              => '備考',
        ];
    }
}
