<?php

namespace App\Modules\Admin\ClassTime\Requests;

use App\Requests\BaseSortRequest;

class SortClassTimeRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'time_start',
        'time_end',
        'number'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'number' => 'nullable|integer',
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i',
        ]);
    }
}
