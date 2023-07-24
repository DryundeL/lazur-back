<?php

namespace App\Modules\Admin\Holiday\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Models\Holiday;
use App\Modules\Admin\Holiday\Requests\SortHolidayRequest;
use App\Modules\Admin\Holiday\Requests\StoreHolidayRequest;
use App\Modules\Admin\Holiday\Resources\HolidayCollection;
use App\Modules\Admin\Holiday\Resources\HolidayResource;
use App\Modules\Admin\Holiday\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HolidayController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param SortHolidayRequest $request
     * @param HolidayService $service
     * @return HolidayCollection|JsonResponse
     */
    public function index(SortHolidayRequest $request, HolidayService $service): HolidayCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new HolidayCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreHolidayRequest $request
     * @param HolidayService $service
     * @return HolidayResource
     */
    public function store(StoreHolidayRequest $request, HolidayService $service): HolidayResource
    {
        $holiday = $service->create($request->validated());

        return new HolidayResource($holiday);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return HolidayResource
     */
    public function show(int $id): HolidayResource
    {
        $holiday = Cache::remember(Holiday::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Holiday::findOrFail($id);
        });

        return new HolidayResource($holiday);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreHolidayRequest $request
     * @param HolidayService $service
     * @param Holiday $holiday
     * @return HolidayResource
     */
    public function update(StoreHolidayRequest $request, HolidayService $service, Holiday $holiday): HolidayResource
    {
        $holiday = $service->update($request->validated(), $holiday->id);

        return new HolidayResource($holiday);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param HolidayService $service
     * @param Holiday $holiday
     * @return JsonResponse
     */
    public function destroy(HolidayService $service, Holiday $holiday): JsonResponse
    {
        $service->destroy($holiday->id);

        return $this->sendResponse();
    }
}
