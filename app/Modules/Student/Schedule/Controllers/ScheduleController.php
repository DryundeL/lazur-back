<?php

namespace App\Modules\Student\Schedule\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Spatie\IcalendarGenerator\ValueObjects\RRule;
use App\Modules\Student\Schedule\Requests\SortScheduleRequest;
use App\Modules\Student\Schedule\Resources\ScheduleCollection;
use App\Modules\Student\Schedule\Services\ScheduleService;
use App\Http\Controllers\BaseController as Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortScheduleRequest $request
     * @param ScheduleService $service
     * @return ScheduleCollection|JsonResponse
     */
    public function index(SortScheduleRequest $request, ScheduleService $service): ScheduleCollection|JsonResponse
    {
        $filters = $request->validated();
        $filters['group_id'] = Auth::user()->group->first()->id;

        $responseArray = $service->search($filters);

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new ScheduleCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Returns student`s schedule in ics format
     *
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     * @throws \Exception
     */
    public function export()
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
        $startStudyDay = Carbon::parse("{$startStudyYear}-09-01");
        $numberOfFirstStudyWeek = $startStudyDay->format('W');
        $events = [];

//        $startStudyDayOfWeek = $startStudyDay->format('l');
//        if ($startStudyDayOfWeek != 'Saturday' or $startStudyDayOfWeek != 'Sunday') {
//            $numberOfFirstStudyWeek = $startStudyDay->format('W');
//        } else {
//            $numberOfFirstStudyWeek = 1 + (int) $startStudyDay->format('W');
//        }

        $schedules = Schedule::select()
            ->where('group_id',  Auth::user()->group->first()->id)
            ->leftJoin('class_times as c', 'c.id', '=', 'schedules.class_time_id')
            ->leftJoin('disciplines as d', 'd.id', '=', 'schedules.discipline_id')
            ->get();

        foreach ($schedules as $schedule) {
            $day = $startStudyDay->firstOfMonth($dayOfWeekTypes[$schedule->day_of_week])->format('d');
            $date = Carbon::parse("{$startStudyYear}-09-{$day}");
            $weekType = ((int) $date->format('W') -  $numberOfFirstStudyWeek) % 2 === 0 ? 'Четная' : 'Нечетная';

            if ($schedule->week_type) {
                $interval = 2;
                if ($schedule->week_type != $weekType) {
                    $day = (int) $day + 7;
                }
            } else {
                $interval = 1;
            }

            $start_time = new DateTime("{$day}-09-{$startStudyYear} {$schedule->time_start}");
            $end_time = new DateTime("{$day}-09-{$startStudyYear} {$schedule->time_end}");

            $events[] = Event::create()
                ->name($schedule->name)
                ->period($start_time, $end_time)
                ->rrule(RRule::frequency(RecurrenceFrequency::weekly())
                    ->interval($interval)
                    ->until($endStudyDate));
        }

        $calendar = Calendar::create()
            ->event($events)
            ->get();

        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="calendar.ics"',
        ]);
    }
}
