<?php

namespace App\Modules\Admin\Auth\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class CreateTokenForAdminRequest extends FormRequest
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
        $userTypes = [
            'student',
            'employee',
        ];

        return [
            'id' => 'required|integer',
            'user_type' => [
                'required', 'string',
                Rule::in($userTypes)
            ],
        ];
    }

}
