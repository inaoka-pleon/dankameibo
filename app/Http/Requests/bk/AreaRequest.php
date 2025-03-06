<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
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
            'area'              => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'area'              => '地区名',
            //
        ];
    }
    
    public function messages(): array
    {
        return [
            'area.required'     => '地区名は必須入力です。',
        ];
    }
}
