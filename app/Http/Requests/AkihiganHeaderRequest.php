<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AkihiganHeaderRequest extends FormRequest
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
            'era'               => 'required',
            'year'              => 'required',
            'name'              => 'nullable|string|max:40',
            'namekana'          => 'nullable',
            'postcode'          => 'nullable|string|max:10',
            'address1'          => 'nullable|string',
            'address2'          => 'nullable|string',
            'tel'               => 'nullable|string|max:15',
            'month'             => 'nullable',
            'day'               => 'nullable',
            'ampm'              => 'nullable',
            'hour'              => 'nullable',
            'minute'            => 'nullable',
            'manager'           => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'era'               => '元号',
            'year'              => '年数',
            'name'              => '氏名',
            'namekana'          => '氏名かな',
            'postcode'          => '郵便番号',
            'address1'          => '住所１',
            'address2'          => '住所２',
            'tel'               => '電話番号',
            'month'             => '月',
            'day'               => '日',
            'ampm'              => '午前午後',
            'hour'              => '時',
            'minute'            => '分',
            'manager'           => '担当者',
            //
        ];
    }
}
