<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'teacher'               => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'teacher'              => '師',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'teacher.required'     => '師は必須入力です。',
        ];
    }
}
