<?php

namespace App\Modules\Admin\Employee\Requests;

use App\Requests\BaseSortRequest;

class SortEmployeeRequest extends BaseSortRequest
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
            'query' => 'nullable|string',
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
        ]);
    }
}
