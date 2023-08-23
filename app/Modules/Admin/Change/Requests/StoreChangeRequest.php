<?php

namespace App\Modules\Admin\Change\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class StoreChangeRequest extends FormRequest
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
            'date' => 'required|date',
            'group_id' => 'required|integer|exists:App\Models\Group,id',
            'discipline_id' => 'nullable|integer|exists:App\Models\Discipline,id',
            'class_time_id' => 'required|integer|exists:App\Models\ClassTime,id',
            'change_details' => 'required|array',
            'change_details.*.audience_id' => 'required|integer|exists:App\Models\Audience,id',
            'change_details.*.employee_id' => 'required|integer|exists:App\Models\Employee,id',
        ];
    }

}
