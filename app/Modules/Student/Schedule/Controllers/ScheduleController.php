<?php

namespace App\Modules\Student\Schedule\Controllers;

use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Modules\Student\Schedule\Requests\SortScheduleRequest;
use App\Modules\Student\Schedule\Resources\ScheduleCollection;
use App\Modules\Student\Schedule\Services\ScheduleService;
use App\Http\Controllers\BaseController as Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Spatie\IcalendarGenerator\Properties\TextProperty;


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
        $filter['group_id'] = Auth::user()->group->first()->id;

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

    public function import()
    {
        $calendar = Calendar::create()
            ->event([
                Event::create('Creating calender feeds')
                    ->period(new DateTime('6 march 2019'), new DateTime('7 march 2019')),
                Event::create('Creating calenderfs')
                    ->period(new DateTime('6 march 2022'), new DateTime('7 march 2022')),
            ])
        ->get();

        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="my-awesome-calendar.ics"',
        ]);
    }
}
