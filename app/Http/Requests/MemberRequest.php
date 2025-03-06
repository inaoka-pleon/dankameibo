<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            'qualification'          => 'nullable|string',
            'name'                   => 'required|string|max:40',
            'namekana'               => 'required|string',
            'title'                  => 'nullable|string',
            'subtitle'               => 'nullable|string',
            'address1'               => 'required|string',
            'address2'               => 'nullable|string',
            'tel'                    => 'nullable|string|max:15',
            'fax'                    => 'nullable|string|max:15',
            'letterdivision'         => 'nullable|string',
            'newyearscarddivision'   => 'nullable|string',
            'summergreetingdivision' => 'nullable|string',
            'relationship'           => 'nullable|string',
            'teacher'                => 'nullable|string',
            'memo'                   => 'nullable|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'qualification'          => '資格',
            'name'                   => '氏名',
            'namekana'               => '氏名かな',
            'title'                  => '敬称',
            'subtitle'               => '脇敬称',
            'address1'               => '住所１',
            'address2'               => '住所２',
            'tel'                    => '電話番号',
            'fax'                    => 'FAX',
            'letterdivision'         => '手紙区分',
            'newyearscarddivision'   => '年賀状区分',
            'summergreetingdivision' => '暑中見舞区分',
            'relationship'           => '関係',
            'teacher'                => '師',
            'memo'                   => '備考',
            //
        ];
    }
}
