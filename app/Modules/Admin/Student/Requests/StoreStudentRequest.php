<?php

namespace App\Modules\Admin\Student\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class StoreStudentRequest extends FormRequest
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
            'email' => 'required|string|max:255|unique:students',
            'first_name'   => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'patronymic_name'  => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

}
