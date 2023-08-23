<?php

namespace App\Modules\Employee\JournalDate\Requests;

use App\Requests\BaseSortRequest;

class SortJournalDateRequest extends BaseSortRequest
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
            'date' => 'nullable|string',
            'count' => 'nullable|integer',
            'discipline_id' => 'nullable|integer|exists:App\Models\Discipline,id',
            'group_id' => 'nullable|integer|exists:App\Models\Group,id',
        ]);
    }
}
