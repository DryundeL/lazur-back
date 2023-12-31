<?php

namespace App\Modules\Admin\Change\Requests;

use App\Requests\BaseSortRequest;

class SortChangeRequest extends BaseSortRequest
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
            'date' => 'nullable|date',
            'discipline_id' => 'nullable|integer|exists:App\Models\Discipline,id',
            'group_id' => 'nullable|integer|exists:App\Models\Group,id',
            'class_time_id' => 'nullable|integer|exists:App\Models\ClassTime,id'
        ]);
    }
}
