<?php

namespace App\Modules\Admin\Semester\Requests;

use App\Requests\BaseSortRequest;

class SortSemesterRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'number',
        'start_date',
        'finish_date',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'query_name' => 'nullable|string',
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
        ]);
    }
}
