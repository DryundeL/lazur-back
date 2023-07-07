<?php

namespace App\Modules\Admin\Group\Requests;

use App\Requests\BaseSortRequest;

class SortGroupStudentRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'first_name',
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
