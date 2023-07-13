<?php

namespace App\Modules\Admin\Discipline\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class StoreDisciplineRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'hours' => 'required|integer',
            'speciality_id' => 'required|integer|exists:App\Models\Speciality,id',
        ];
    }

}
