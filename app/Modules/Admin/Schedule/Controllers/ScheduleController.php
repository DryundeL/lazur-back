<?php

namespace App\Modules\Admin\Schedule\Controllers;

use App\Models\Schedule;
use App\Modules\Admin\Schedule\Requests\SortScheduleRequest;
use App\Modules\Admin\Schedule\Requests\StoreScheduleRequest;
use App\Modules\Admin\Schedule\Resources\ScheduleCollection;
use App\Modules\Admin\Schedule\Resources\ScheduleResource;
use App\Modules\Admin\Schedule\Services\ScheduleService;
use App\Http\Controllers\BaseController as Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

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
     * Store a newly created resource in storage.
     *
     * @param StoreScheduleRequest $request
     * @param ScheduleService $service
     * @return ScheduleResource
     */
    public function store(StoreScheduleRequest $request, ScheduleService $service) : ScheduleResource
    {
        $schedule = $service->create($request->validated());

        return new ScheduleResource($schedule);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ScheduleResource
     */
    public function show(int $id): ScheduleResource
    {
        $schedule = Cache::remember(Schedule::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Schedule::findOrFail($id);
        });

        return new ScheduleResource($schedule);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreScheduleRequest $request
     * @param ScheduleService $service
     * @param Schedule $schedule
     * @return ScheduleResource
     */
    public function update(StoreScheduleRequest $request, ScheduleService $service, Schedule $schedule): ScheduleResource
    {
        $schedule = $service->update($request->validated(), $schedule->id);

        return new ScheduleResource($schedule);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param ScheduleService $service
     * @param Schedule $schedule
     * @return JsonResponse
     */
    public function destroy(ScheduleService $service, Schedule $schedule): JsonResponse
    {
        $service->destroy($schedule->id);

        return $this->sendResponse();
    }
}
