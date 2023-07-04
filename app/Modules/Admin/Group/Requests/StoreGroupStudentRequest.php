<?php

namespace App\Modules\Admin\Group\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupStudentRequest extends FormRequest
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
            'students_ids' => 'required|array',
            'students_ids.*' => 'required_if:students_ids,true|exists:App\Models\Student,id'
        ];
    }

}
