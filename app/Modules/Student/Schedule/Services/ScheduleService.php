<?php

namespace App\Modules\Student\Schedule\Services;

use App\Services\BaseService;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Spatie\IcalendarGenerator\ValueObjects\RRule;
use App\Models\Holiday;
use App\Models\Schedule;

class ScheduleService extends BaseService
{
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
    }

    /**
     * Returns student`s schedule in ics format
     *
     * @return string
     * @throws \Exception
     */
    public function export(): string
    {
        $currentMonth = Carbon::now()->format('m');
        $startStudyYear = Carbon::now()->format('Y');
        $dayOfWeekTypes = array_flip([
            'Воскресенье',
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
        ]);

        if ($currentMonth < 9) {
            $startStudyYear--;
        }

        $endStudyDate = new DateTime('01-07-' . $startStudyYear + 1);
        $startStudyDate = Carbon::parse("{$startStudyYear}-09-01");
        $numberOfFirstStudyWeek = $startStudyDate->format('W');
        $events = [];

        $schedules = Schedule::select()
            ->where('group_id', Auth::user()->group->first()->id)
            ->leftJoin('class_times as c', 'c.id', '=', 'schedules.class_time_id')
            ->leftJoin('disciplines as d', 'd.id', '=', 'schedules.discipline_id')
            ->get();

        $holidays = Holiday::select()
            ->where('is_shortened', false)
            ->whereBetween('date', [$startStudyDate, $endStudyDate])
            ->orderBy('date')
            ->pluck('date')
            ->toArray();

        foreach ($schedules as $schedule) {
            $day = $startStudyDate->firstOfMonth($dayOfWeekTypes[$schedule->day_of_week])->format('d');
            $date = Carbon::parse("{$startStudyYear}-09-{$day}");
            $weekType = ((int)$date->format('W') - $numberOfFirstStudyWeek) % 2 === 0 ? 'Четная' : 'Нечетная';

            if ($schedule->week_type != 'Общая') {
                $interval = 2;
                if ($schedule->week_type != $weekType) {
                    $day = (int)$day + 7;
                }
            } else {
                $interval = 1;
            }

            $descriptions = '';

            foreach ($schedule->scheduleDetails as $detail) {
                $audience = $detail->audience;
                $employee = $detail->employee;

                if ($descriptions != '') {
                    $descriptions .= ' / ';
                }

                $descriptions .= $audience->corpus . 'к., ' . $audience->cabinet_number . ' кабинет, ' .
                    $employee->last_name . ' ' . $employee->first . ' ' . $employee->patronymic_name . ' ';
            }

            $start_time = new DateTime("{$day}-09-{$startStudyYear} {$schedule->time_start}");
            $end_time = new DateTime("{$day}-09-{$startStudyYear} {$schedule->time_end}");

            $events[] = Event::create()
                ->name($schedule->name)
                ->description($descriptions)
                ->period($start_time, $end_time)
                ->rrule(RRule::frequency(RecurrenceFrequency::weekly())
                    ->interval($interval)
                    ->until($endStudyDate))
                ->doNotRepeatOn($holidays);
        }

        return Calendar::create()
            ->event($events)
            ->get();
    }
}
