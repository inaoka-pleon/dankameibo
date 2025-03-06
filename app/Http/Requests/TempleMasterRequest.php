<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempleMasterRequest extends FormRequest
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
            'mountainname'              => 'nullable|string',
            'templename'                => 'nullable|string',
            'jyushokuname'              => 'nullable|string|max:40',
            'postcode'                  => 'nullable|string|max:10',
            'address1'                  => 'nullable|string',
            'address2'                  => 'nullable|string',
            'tel'                       => 'nullable|string|max:15',
            'fax'                       => 'nullable|string|max:15',
        ];
    }

    public function attributes(): array
    {
        return [
            'mountainname'              => '山号',
            'templename'                => '寺院名',
            'jyushokuname'              => '住職氏名',
            'postcode'                  => '郵便番号',
            'address1'                  => '住所１',
            'address2'                  => '住所２',
            'tel'                       => '電話番号',
            'fax'                       => 'FAX',
        ];
    }
    //
}