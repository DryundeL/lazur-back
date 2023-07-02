<?php

namespace App\Modules\Admin\Employee\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
        $roles = [
            'cleaner',
            'teacher',
            'director',
        ];

        return [
            'email' => [
                'required', 'string', 'max:255',
                Rule::unique('employees')->ignore($this->employee->id),
            ],
            'first_name'   => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'patronymic_name'  => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
            'role' => [
                'required', 'string',
                Rule::in($roles)
            ]
        ];
    }

}
