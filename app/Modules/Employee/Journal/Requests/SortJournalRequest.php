<?php

namespace App\Modules\Employee\Journal\Requests;

use App\Requests\BaseSortRequest;

class SortJournalRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
            'mark' => 'nullable|string',
            'journal_date_id' => 'nullable|integer|exists:App\Models\JournalDate,id',
            'student_id' => 'nullable|integer|exists:App\Models\Student,id',
        ]);
    }
}
