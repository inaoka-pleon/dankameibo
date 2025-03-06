<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EraRequest extends FormRequest
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
            'name'              => 'required|string|max:2',
            'start_ymd'         => 'required|date',
            'end_ymd'           => 'required|date',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => '元号',
            'start_ymd'         => '開始年月日',
            'end_ymd'           => '終了年月日',
        ];
    }
}
