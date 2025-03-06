<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KakochoRequest extends FormRequest
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
            'kaimyou'           => 'nullable|string|max:40',
            'zokumyou'          => 'required|string|max:40',
            'zokumyoukana'      => 'required|string|max:40',
            'death_era'         => 'required',
            'death_year'        => 'required',
            'death_month'       => 'required',
            'death_day'         => 'required',
            'ageatdeath'        => 'nullable|numeric',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'kaimyou'           => '戒名',
            'zokumyou'          => '俗名',
            'zokumyoukana'      => '俗名かな',
            'death_era'         => '命日(元号)',
            'death_year'        => '命日(年)',
            'death_month'       => '命日(月)',
            'death_day'         => '命日(日)',
            'ageatdeath'        => '行年',
            //
        ];
    }
}
