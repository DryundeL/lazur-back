<?php

namespace App\Modules\Admin\Audience\Requests;

use App\Requests\BaseSortRequest;

class SortAudienceRequest extends BaseSortRequest
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
            'query_corpus' => 'nullable|string',
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
            'cabinet_number' => 'nullable|integer',
        ]);
    }
}
