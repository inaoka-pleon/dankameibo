<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NenkilistDocumentRequest extends FormRequest
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
            'document1'              => 'nullable|string|max:36',
            'document2'              => 'nullable|string|max:36',
            'document3'              => 'nullable|string|max:36',
            'document4'              => 'nullable|string|max:36',
            'document5'              => 'nullable|string|max:36',
            'document6'              => 'nullable|string|max:36',
        ];
    }

    public function attributes(): array
    {
        return [
            'document1'              => '文１',
            'document2'              => '文２',
            'document3'              => '文３',
            'document4'              => '文４',
            'document5'              => '文５',
            'document6'              => '文６',
        ];
    }
}
