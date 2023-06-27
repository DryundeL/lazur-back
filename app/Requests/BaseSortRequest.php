<?php

namespace App\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class BaseSortRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * The sortable fields.
     *
     * @var array
     */
    protected $sortableFields = [];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sort_by' => [
                'nullable', 'required_with:sort_dir',
                Rule::in($this->sortableFields),
            ],
            'sort_dir' => 'nullable|in:asc,desc',
            'date_range' => 'nullable|in:month,week,day',
        ];
    }
}
