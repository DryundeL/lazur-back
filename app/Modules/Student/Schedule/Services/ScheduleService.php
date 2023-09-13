<?php

namespace App\Modules\Student\Schedule\Services;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Schedule;
use App\Models\ScheduleDetail;

class ScheduleService extends BaseService
{
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Schedule $schedule
     * @param $attributes
     * @return void
     */
    private function createScheduleDetail(Schedule $schedule, $attributes): void
    {
        foreach ($attributes['schedule_details'] as $scheduleInfo) {
            $scheduleDetails = new ScheduleDetail();

            $scheduleDetails->schedule()->associate($schedule->id);
            $scheduleDetails->employee()->associate($scheduleInfo['employee_id']);
            $scheduleDetails->audience()->associate($scheduleInfo['audience_id']);

            $scheduleDetails->save();
        }
    }

    /**
     * Associates schedule with some models
     *
     * @param Schedule $schedule
     * @param $attributes
     * @return void
     */
    private function associateWithSchedule(Schedule $schedule, $attributes): void
    {
        $schedule->discipline()->associate($attributes['discipline_id']);
        $schedule->classTime()->associate($attributes['class_time_id']);
        $schedule->group()->associate($attributes['group_id']);
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
        $this->associateWithSchedule($schedule, $attributes);
        $schedule->save();

        $this->createScheduleDetail($schedule, $attributes);

        Cache::put($schedule->getCacheKey($schedule->id), $schedule, Carbon::now()->addMinutes(15));

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
        $this->associateWithSchedule($schedule, $attributes);
        $schedule->scheduleDetails()->delete();
        $schedule->save();

        Cache::put($schedule->getCacheKey($id), $schedule, Carbon::now()->addMinutes(15));

        $this->createScheduleDetail($schedule, $attributes);

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
