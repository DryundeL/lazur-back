<?php

namespace App\Modules\Student\Schedule\Requests;

use App\Requests\BaseFormRequest as FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
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

        return [
            'day_of_week' => [
                'required', 'string',
                Rule::in($dayOfWeekTypes)
            ],
            'week_type' => [
                'required', 'string',
                Rule::in($weekTypes)
            ],
            'group_id' => 'required|integer|exists:App\Models\Group,id',
            'discipline_id' => 'required|integer|exists:App\Models\Discipline,id',
            'class_time_id' => 'required|integer|exists:App\Models\ClassTime,id',
            'schedule_details' => 'required|array',
            'schedule_details.*.audience_id' => 'required|integer|exists:App\Models\Audience,id',
            'schedule_details.*.employee_id' => 'required|integer|exists:App\Models\Employee,id',
        ];
    }

}
