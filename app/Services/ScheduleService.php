<?php

namespace App\Services;

use App\Models\Change;
use App\Models\Holiday;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Get the modified schedules for a given date, considering changes.
     *
     * @param array $schedules The original schedules for the date.
     * @param string $date The date for which to retrieve schedules.
     * @param array $changes The changes to be applied to the schedules.
     * @return array|Collection The modified schedules after applying changes.
     */
    public static function getChangesForSchedule($schedules, $date, $changes): array|Collection
    {
        foreach ($schedules as $schedule => $item) {

            if (Change::where('date', $date)
                ->where('group_id', $item->group_id)
                ->where('class_time_id', $item->class_time_id)
                ->where(function ($query) use ($item) {
                    $query->where('discipline_id', null)
                        ->orWhere('discipline_id', '<>', $item->discipline_id)
                        ->orWhere('discipline_id', $item->discipline_id);
                })
                ->exists()) {
                unset($schedules[$schedule]);
            }
        }

        foreach ($changes as $change) {
            $found = false;

            foreach ($schedules as $key => $item) {

                if ($item['class_time_id'] == $change['class_time_id'] &&
                    $item['group_id'] == $change['group_id']) {
                    $schedules[$key] = $change;
                    $schedules[$key]['isChanges'] = true;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $change['isChanges'] = true;
                $schedules[] = $change;
            }
        }

        foreach ($schedules as $schedule => $item) {

            if ($item['discipline_id'] == null) {
                unset($schedules[$schedule]);
            }
        }

        if (Holiday::where('date', $date)
            ->where('is_shortened', false)
            ->exists()) {

            return [];
        }

        return $schedules;
    }
}
