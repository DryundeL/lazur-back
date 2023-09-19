<?php

namespace App\Modules\Student\Schedule\Resources;

use App\Resources\BaseResource;
use Illuminate\Http\Request;

class ScheduleResource extends BaseResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'schedule';

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'group' => GroupResource::make($this->group),
            'class_time' => ClassTimeResource::make($this->classTime),
            'discipline' => DisciplineResource::make($this->discipline),
            'week_type' => $this->week_type,
            'day_of_week' => $this->day_of_week,
            'schedule_details' => ScheduleDetailResource::collection($this->scheduleDetails),
        ]);
    }
}
