<?php

namespace App\Modules\Admin\Student\Requests;

use App\Requests\BaseSortRequest;

class SortStudentRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'name',
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'id' => 'nullable|integer',
            'name' => 'nullable|string',
        ]);
    }
}
