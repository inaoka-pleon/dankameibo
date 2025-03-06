<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OccupationRequest extends FormRequest
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
            'occupation'        => 'required|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'occupation'        => '職業',
        ];
    }

    public function messages()
    {
        return [
            'dankadivision.required'    => '職業は必須入力です',        
        ];
    }
}
