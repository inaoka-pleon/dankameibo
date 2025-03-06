<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempleOfficeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() :bool
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
            'templeoffice'              => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'templeoffice'              => '宗務所',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'templeoffice.required'     => '宗務所は必須入力です。',
        ];
    }
}
