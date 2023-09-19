<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Audience;
use App\Models\ClassTime;
use App\Models\Discipline;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleDetail;
use App\Models\Speciality;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
            null
        ];
        $specialityIds = Speciality::pluck('id')->toArray();
        $disciplines = [
            'Нейробиология',
            'Искусство',
            'Актерское мастерство',
            'Психология',
            'Физическая культура',
            'Литература',
            'Английский язык',
            'Японский язык',
            'Кулинарное мастерство',
            'Автомеханика',
            'Программирование',
            'Дизайн'
        ];

        foreach ($disciplines as $discipline) {
            $row = new  Discipline([
                'name' => $discipline,
                'hours' => 20]);
            $row->save();
            $row->specialities()->attach($specialityIds);
        }

        $classTimes = [
            [1, '8:30', '10:05'],
            [2, '10:15', '11:50'],
            [3, '12:20', '13:55'],
            [4, '14:05', '15:35'],
        ];

        foreach ($classTimes as $classTime) {
            $row = new  ClassTime([
                'number' => $classTime[0],
                'time_start' => $classTime[1],
                'time_end' => $classTime[2]]);
            $row->save();
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('audiences')->insert([
                'corpus' => Str::random(8),
                'cabinet_number' => rand(1, 39),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $groupId = 2;
        $employeeIds = Employee::pluck('id')->toArray();
        $disciplineIds = Discipline::pluck('id')->toArray();
        $classTimeIds = ClassTime::pluck('id')->toArray();
        $audienceIds = Audience::pluck('id')->toArray();

        foreach ($dayOfWeekTypes as $dayOfWeekType) {
            $lessonCount = rand(3,4);
            for ($i = 0; $i < $lessonCount; $i++) {
                $schedule = new Schedule([
                    'week_type' => Arr::random($weekTypes),
                    'day_of_week' => $dayOfWeekType
                ]);
                $schedule->discipline()->associate(Arr::random($disciplineIds));
                $schedule->classTime()->associate($classTimeIds[$i]);
                $schedule->group()->associate($groupId);

                $schedule->save();

                $scheduleDetails = new ScheduleDetail();

                $scheduleDetails->schedule()->associate($schedule->id);
                $scheduleDetails->employee()->associate(Arr::random($employeeIds));
                $scheduleDetails->audience()->associate(Arr::random($audienceIds));

                $scheduleDetails->save();

            }
        }
    }
}
