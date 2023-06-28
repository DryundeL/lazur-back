<?php

namespace App\Modules\Employee\Auth\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class LoginAuthRequest extends FormRequest
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
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ];
    }

}
