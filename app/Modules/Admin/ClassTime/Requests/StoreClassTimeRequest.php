<?php

namespace App\Modules\Admin\ClassTime\Requests;

use App\Requests\BaseFormRequest as FormRequest;

class StoreClassTimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'number' => 'required|integer',
            'time_start'   => 'required|date_format:H:i',
            'time_end'  => 'required|date_format:H:i|after:time_start',
        ];
    }

}
