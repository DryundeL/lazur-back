<?php

namespace App\Modules\Employee\Journal\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class StoreJournalRequest extends FormRequest
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
        $markTypes = [
            '5',
            '4',
            '3',
            '2',
            '1',
            'н/б'
        ];

        return [
            'journal_date_id' => 'required|integer|exists:App\Models\JournalDate,id',
            'mark' => [
                'required', 'string',
                Rule::in($markTypes)
            ],
            'student_id' => 'required|exists:App\Models\Student,id',
        ];
    }

}
