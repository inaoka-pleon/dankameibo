<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KaikiRequest extends FormRequest
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
            'kaiki_kbn'         => 'required',
            'kaiki'             => 'required_if:kaiki_kbn,0|numeric|nullable',
            'kaiki_name'        => 'required|string|max:10',
            'target_flg'        => 'required',
            'from_year_kbn'     => 'required_if:kaiki_kbn,2,3',
            'from_month'        => 'required_if:kaiki_kbn,2,3|nullable|numeric',
            'from_day'          => 'required_if:kaiki_kbn,2,3|nullable|numeric',
            'to_year_kbn'       => 'required_if:kaiki_kbn,2,3',
            'to_month'          => 'required_if:kaiki_kbn,2,3|nullable|numeric',
            'to_day'            => 'required_if:kaiki_kbn,2,3|nullable|numeric',
            'houyou_month'      => 'required_if:kaiki_kbn,2,3|nullable|numeric',
            'houyou_day'        => 'required_if:kaiki_kbn,2,3|nullable|numeric',
        ];
    }

    public function attributes(): array
    {
        return [
            'kaiki_kbn'         => '区分',
            'kaiki'             => '回忌',
            'kaiki_name'        => '回忌名',
            'target_flg'        => '表示・非表示',
            'from_year_kbn'     => '対象期間・年(自)',
            'from_month'        => '対象期間・月(自)',
            'from_day'          => '対象期間・日(自)',
            'to_year_kbn'       => '対象期間・年(至)',
            'to_month'          => '対象期間・月(至)',
            'to_day'            => '対象期間・日(至)',
            'houyou_month'      => '法要日・月',
            'houyou_day'        => '法要日・日',

        ];
    }

    public function messages(): array
    {
        return [
            'kaiki.required_if'             => '区分が「年忌」の場合、:attributeは必須入力です。',
            'from_year_kbn.required_if'     => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'from_month.required_if'        => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'from_day.required_if'          => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'to_year_kbn.required_if'       => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'to_month.required_if'          => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'to_day.required_if'            => '区分が「初花」または「初盆」の場合、:attributeは必須入力です。',
            'houyou_month.required_if'      => '区分が「初花」または「初盆」の場合、:attributeは入力です。',
            'houyou_day.required_if'        => '区分が「初花」または「初盆」の場合、:attributeは入力です。',
        ];
    }
}
