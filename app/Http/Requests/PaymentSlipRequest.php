<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentSlipRequest extends FormRequest
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
            'accountno1'             => 'nullable|string|max:5',
            'accountno2'             => 'nullable|string|max:1',
            'accountno3'             => 'nullable|string|max:7',
            'name'                   => 'nullable|string|max:40',
            'price'                  => 'nullable|integer',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'accountno1'             => '口座番号１',
            'accountno2'             => '口座番号２',
            'accountno3'             => '口座番号３',
            'name'                   => '加入者名',
            'price'                  => '金額',
            //
        ];
    }
}
