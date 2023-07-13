<?php

namespace App\Modules\Admin\Audience\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class StoreAudienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'corpus' => 'required|string',
            'cabinet_number' => [
                'required', 'integer',
                Rule::unique('audiences', 'cabinet_number')
                    ->where('corpus', $this->corpus)
            ]
        ];
    }

}
