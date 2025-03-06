<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JikakuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'jikaku'              => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'jikaku'              => '寺格',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'jikaku.required'     => '寺格は必須入力です。',
        ];
    }
}
