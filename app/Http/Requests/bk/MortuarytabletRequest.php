<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MortuarytabletRequest extends FormRequest
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
            'mortuarytablet'    => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'mortuarytablet'    => '位牌区分',
            //
        ];
    }

    public function messages()
    {
        return [
            'dankadivision.required'    => '位牌区分は必須入力です',        
        ];
    }
}
