<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NenkilistRequest extends FormRequest
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
            'kuyou'                  => 'nullable|integer',
            'memo'                   => 'nullable|string',
            //
        ];
    }

    public function attributes(): array
    {
        return [
            'kuyou'                  => '供養',
            'memo'                   => '備考',
            //
        ];
    }
}
