<?php

namespace App\Modules\Student\Schedule\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as Controller;
use App\Modules\Student\Schedule\Requests\SortScheduleRequest;
use App\Modules\Student\Schedule\Resources\ScheduleCollection;
use App\Modules\Student\Schedule\Services\ScheduleService;

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
     * @param ScheduleService $service
     * @return Application|ResponseFactory|\Illuminate\Foundation\Application|Response
     * @throws \Exception
     */
    public function export(ScheduleService $service): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        $calendar = $service->export();

        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="calendar.ics"',
        ]);
    }
}
