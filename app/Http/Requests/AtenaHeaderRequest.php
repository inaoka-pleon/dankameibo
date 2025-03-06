<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AtenaHeaderRequest extends FormRequest
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
            'title'                  => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'                 => '表題',
        ];
    }
}
