<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DankaRequest extends FormRequest
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
            'area'              => 'nullable',
            'dankadivision'     => 'nullable',
            'mortuarytablet'    => 'nullable',
            'gozikai'           => 'nullable',
            'membershipfee'     => 'nullable',
            'report'            => 'nullable',
            'tanagyou'          => 'nullable',
            'haruhigan'         => 'nullable',
            'akihigan'          => 'nullable',
            'hanamatsuri'       => 'nullable',
            'postcard'          => 'nullable',
            'memo'              => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'area'              => '地区名',
            'dankadivision'     => '檀家区分',
            'mortuarytablet'    => '位牌区分',
            'gozikai'           => '護持会',
            'membershipfee'     => '会費',
            'report'            => '届出',
            'tanagyou'          => '棚経',
            'haruhigan'         => '春彼岸',
            'akihigan'          => '秋彼岸',
            'hanamatsuri'       => '花まつり',
            'postcard'          => 'はがき区分',
            'memo'              => 'メモ',
            //
        ];
    }
}
