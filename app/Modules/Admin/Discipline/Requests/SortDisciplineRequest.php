<?php

namespace App\Modules\Admin\Discipline\Requests;

use App\Requests\BaseSortRequest;

class SortDisciplineRequest extends BaseSortRequest
{
    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [
        'id',
        'name',
        'hours'
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
            'name' => 'nullable|string',
            'hours' => 'nullable|integer',
        ]);
    }
}
