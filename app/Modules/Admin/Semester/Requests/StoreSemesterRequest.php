<?php

namespace App\Modules\Admin\Semester\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class StoreSemesterRequest extends FormRequest
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
            'number' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after_or_equal:start_date',
        ];
    }

}
