<?php

namespace App\Modules\Admin\Holiday\Requests;

use App\Requests\BaseFormRequest;

class StoreHolidayRequest extends BaseFormRequest
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
            'is_shortened' => 'required|boolean'
        ];
    }

}
