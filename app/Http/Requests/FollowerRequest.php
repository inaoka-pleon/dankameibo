<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowerRequest extends FormRequest
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
            'name'              => 'required|string|max:40',
            'namekana'          => 'required|string',
            'relationship'      => 'nullable',
            'birthdate'         => 'nullable',
            'gender'            => 'nullable',
            'position'          => 'nullable',
            'postcode'          => 'nullable|string|max:10|regex:/^[a-zA-Z0-9-]+$/',
            'address1'          => 'nullable|string',
            'address2'          => 'nullable|string',
            'tel'               => 'nullable|string|max:15|regex:/^[a-zA-Z0-9-]+$/',
            'fax'               => 'nullable|string|max:15|regex:/^[a-zA-Z0-9-]+$/',
            'occupation'        => 'nullable',
            'seizenkaimyou'     => 'nullable|string',
            'memo'              => 'nullable',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'name'              => '氏名',
            'namekana'          => '氏名かな',
            'relationship'      => '続柄',
            'birthdate'         => '生年月日',
            'gender'            => '性別',
            'position'          => '寺役職',
            'postcode'          => '郵便番号',
            'address1'          => '住所１',
            'address2'          => '住所２',
            'tel'               => '電話番号',
            'fax'               => 'FAX',
            'occupation'        => '職業',
            'seizenkaimyou'     => '生前戒名',
            'memo'              => 'メモ',
            //
        ];
    }
}
