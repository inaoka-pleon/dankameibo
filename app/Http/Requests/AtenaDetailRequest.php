<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtenaDetailRequest extends FormRequest
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
            'name'                  => 'nullable|max:40',
            'namekana'              => 'nullable',
            'keishou'               => 'nullable',
            'postcode'              => 'nullable|max:10',
            'address1'              => 'nullable',
            'address2'              => 'nullable',
            'postcard'              => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                  => '氏名',
            'namekana'              => '氏名かな',
            'keishou'               => '敬称',
            'postcode'              => '郵便番号',
            'address1'              => '住所１',
            'address2'              => '住所２',
            'postcard'              => 'はがき区分',
        ];
    }
}
