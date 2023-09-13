<?php

namespace App\Modules\Student\Schedule\Requests;

use App\Requests\BaseSortRequest;
use Illuminate\Validation\Rule;

class SortScheduleRequest extends BaseSortRequest
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
        $dayOfWeekTypes = [
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
            'Воскресенье',
        ];

        $weekTypes = [
            'Чётная',
            'Нечётная',
        ];

        return array_merge(parent::rules(), [
            'paginated' => 'nullable|integer',
            'id' => 'nullable|integer',
            'query_day_of_week' => [
                'nullable', 'string',
                Rule::in($dayOfWeekTypes)
            ],
            'query_week_type' => [
                'nullable', 'string',
                Rule::in($weekTypes)
            ],
            'discipline_id' => 'nullable|integer|exists:App\Models\Discipline,id',
            'class_time_id' => 'nullable|integer|exists:App\Models\ClassTime,id'
        ]);
    }
}
