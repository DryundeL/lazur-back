<?php

namespace App\Modules\Admin\Group\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class StoreGroupRequest extends FormRequest
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
        $educationTypes = [
            'Очная',
            'Заочная',
            'Очно-заочная',
        ];

        return [
            'name' => 'required|string|max:255',
            'education_type' => [
                'required', 'string',
                Rule::in($educationTypes)
            ],
            'employee_id' => 'required|exists:App\Models\Employee,id',
            'speciality_id' => 'required|integer|exists:App\Models\Speciality,id',
            'semesters_ids' => 'required|array',
            'semesters_ids.*' => 'required|exists:App\Models\Semester,id',
        ];
    }

}
