<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QualificationRequest extends FormRequest
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
            'qualification'              => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'qualification'              => '資格',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'qualification.required'     => '資格は必須入力です。',
        ];
    }
}
