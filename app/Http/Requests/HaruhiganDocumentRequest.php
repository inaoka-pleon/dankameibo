<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HaruhiganDocumentRequest extends FormRequest
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
            'document1'              => 'nullable|string|max:32',
            'document2'              => 'nullable|string|max:32',
            'document3'              => 'nullable|string|max:32',
            'document4'              => 'nullable|string|max:32',
            'document5'              => 'nullable|string|max:32',
            'document6'              => 'nullable|string|max:32',
            'document7'              => 'nullable|string|max:32',
            'document8'              => 'nullable|string|max:32',
            'document9'              => 'nullable|string|max:32',
            'document10'             => 'nullable|string|max:32',
            'document11'             => 'nullable|string|max:32',
            'document12'             => 'nullable|string|max:32',
            'kakui'                  => 'nullable|string',
            'address'                => 'nullable|string',
            'templename'             => 'nullable|string',
            'tel'                    => 'nullable|string',
            //
        ];
    }
    public function attributes(): array
    {
        return [
            'title'                  => '表題',
            'document1'              => '文１',
            'document2'              => '文２',
            'document3'              => '文３',
            'document4'              => '文４',
            'document5'              => '文５',
            'document6'              => '文６',
            'document7'              => '文７',
            'document8'              => '文８',
            'document9'              => '文９',
            'document10'             => '文１０',
            'document11'             => '文１１',
            'document12'             => '文１２',
            'kakui'                  => '各位',
            'address'                => '住所',
            'templename'             => '寺院名',
            'tel'                    => 'TEL',
            //
        ];
    }
}