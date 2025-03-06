<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitleRequest extends FormRequest
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
            'title'              => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'title'              => '敬称',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'title.required'     => '敬称は必須入力です。',
        ];
    }
}

