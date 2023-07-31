<?php

namespace App\Modules\Admin\Schedule\Services;

use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;


class ScheduleService extends BaseService
{
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Schedule $model
     */
    public function create(array $attributes): Schedule
    {
        $schedule = $this->model;

        $schedule->fill($attributes);
        $schedule->discipline()->associate($attributes['discipline_id']);
        $schedule->classTime()->associate($attributes['class_time_id']);
        $schedule->group()->associate($attributes['group_id']);
        $schedule->save();

        foreach ($attributes['schedule_details'] as $scheduleDetail) {
            $scheduleDetails = new ScheduleDetail();

            $scheduleDetails->schedule()->associate($schedule->id);
            $scheduleDetails->employee()->associate($scheduleDetail['employee_id']);
            $scheduleDetails->audience()->associate($scheduleDetail['audience_id']);

            $scheduleDetails->save();
        }

        return $schedule;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return Model
     */
    public function update(array $attributes, int $id): Model
    {
        $schedule = $this->find($id);

        $schedule->update($attributes);
        $schedule->discipline()->associate($attributes['discipline_id']);
        $schedule->classTime()->associate($attributes['class_time_id']);
        $schedule->group()->associate($attributes['group_id']);
        $schedule->scheduleDetails()->delete();
        $schedule->save();

        Cache::put($schedule->getCacheKey($id), $schedule, Carbon::now()->addMinutes(15));

        foreach ($attributes['schedule_details'] as $scheduleDetail) {
            $scheduleDetails = new ScheduleDetail();

            $scheduleDetails->schedule()->associate($schedule->id);
            $scheduleDetails->employee()->associate($scheduleDetail['employee_id']);
            $scheduleDetails->audience()->associate($scheduleDetail['audience_id']);

            $scheduleDetails->save();
        }

        return $schedule;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $schedule = $this->find($id);

        $schedule->scheduleDetails()->delete();

        Cache::forget($this->model->getCacheKey($id));

        return $schedule->delete();
    }
}
