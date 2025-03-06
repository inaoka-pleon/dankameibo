<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralMasterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'value1'            => 'required|string|max:15',
            'value2'            => 'nullable|integer',
        ];
            //
    }

    public function attributes(): array
    {
        return [
            'value1'              => '名称',
            'value2'              => '金額',
            //
        ];
    }
    public function messages(): array
    {
        return [
            'value1.required'     => '名称は必須入力です。',
        ];
    }
}
