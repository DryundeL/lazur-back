<?php

namespace App\Modules\Admin\Holiday\Requests;

use App\Requests\BaseSortRequest;

class SortHolidayRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'date',
        'is_shortened'
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
            'is_shortened' => 'nullable|boolean',
            'date' => 'nullable|date'
        ]);
    }
}
