<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TempleRequest extends FormRequest
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
            'templeoffice'              => 'nullable',
            'parish'                    => 'nullable',
            'no'                        => 'nullable',
            'templename'                => 'required|string',
            'templenamekana'            => 'required|string',
            'qualification'             => 'nullable',
            'name'                      => 'required|string|max:40',
            'namekana'                  => 'required|string',
            'title'                     => 'nullable',
            'subtitle'                  => 'nullable',
            'postcode'                  => 'nullable|string|max:10',
            'address1'                  => 'required|string',
            'address2'                  => 'nullable|string',
            'tel'                       => 'nullable|string|max:15',
            'fax'                       => 'nullable|string|max:15',
            'letterdivision'            => 'nullable',
            'newyearscarddivision'      => 'nullable',
            'summergreetingdivision'    => 'nullable', 
            'relationship'              => 'nullable',
            'teacher'                   => 'nullable',
            'mountainname'              => 'nullable|string',
            'jikaku'                    => 'nullable',
            'buddhistfederation'        => 'nullable',
            'memo'                      => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'templeoffice'              => '宗務所',
            'parish'                    => '教区',
            'no'                        => '寺関番号',
            'templename'                => '寺院名',
            'templenamekana'            => '寺院名かな',
            'qualification'             => '資格',
            'name'                      => '氏名',
            'namekana'                  => '氏名かな',
            'title'                     => '敬称',
            'subtitle'                  => '脇敬称',
            'postcode'                  => '郵便番号',
            'address1'                  => '住所１',
            'address2'                  => '住所２',
            'tel'                       => '電話番号',
            'fax'                       => 'FAX',
            'letterdivision'            => '手紙区分',
            'newyearscarddivision'      => '年賀状区分',
            'summergreetingdivision'    => '暑中見舞区分', 
            'relationship'              => '関係',
            'teacher'                   => '師',
            'mountainname'              => '山号',
            'jikaku'                    => '寺格',
            'buddhistfederation'        => '仏教会',
            'memo'                      => '備考',
        ];
    }
    //
}
