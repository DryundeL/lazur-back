<?php

namespace App\Console\Commands;

use App\Models\Change;
use App\Models\JournalDate;
use App\Models\Schedule;
use App\Resources\ScheduleCollection;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:dates';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $description = 'Generate journal dates';

    /**
     * Execute the console command.
     *
     * @return
     */
    public function handle()
    {
        $days = [
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
            'Воскресенье'
        ];

        $currentMonth = Carbon::now()->format('m');
        $startStudyYear = Carbon::now()->format('Y');

        if ($currentMonth < 9) {
            $startStudyYear--;
        }

        $numberOfFirstStudyWeek = Carbon::parse("{$startStudyYear}-09-01")->format('W');
        $weekType = ((int) now()->format('W') -  $numberOfFirstStudyWeek) % 2 === 0 ? 'Четная' : 'Нечетная';

        $currentDayOfWeek = $days[date("N") - 1];

        $currentDay = now();

        $schedules = Schedule::whereHas('group.semesters', function ($query) use ($currentDay) {
            $query->where('start_date', '<=', $currentDay)
                ->where('finish_date', '>=', $currentDay);
        })->where('day_of_week', $currentDayOfWeek)
            ->where(function ($query) use ($weekType) {
                $query->where('week_type', 'Общая')
                    ->orWhere('week_type', $weekType);
            })->get();

        $changes = Change::where('date', now())
            ->get();

        $dates = ScheduleService::getChangesForSchedule(
            $schedules,
            $currentDay,
            $changes);

        $groupDates = $dates->groupBy(['discipline_id', 'group_id']);

        foreach ($groupDates as $groupDate) {

            foreach ($groupDate as $item) {
                $date = new JournalDate();
                $date->discipline_id = $item->first()->discipline_id;
                $date->group_id = $item->first()->group_id;
                $date->date = $currentDay;
                $date->count = $item->count();
                $date->save();
            }

        }

        return Command::SUCCESS;
    }
}
