<?php

namespace App\Modules\Admin\Speciality\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Speciality;
use App\Modules\Admin\Speciality\Requests\StoreSpecialityRequest;
use App\Modules\Admin\Speciality\Requests\UpdateSpecialityRequest;
use App\Modules\Admin\Speciality\Resources\SpecialityCollection;
use App\Modules\Admin\Speciality\Resources\SpecialityResource;
use App\Modules\Admin\Speciality\Services\SpecialityService;
use App\Modules\Admin\Speciality\Requests\SortSpecialityRequest;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortSpecialityRequest $request
     * @param SpecialityService $service
     * @return SpecialityCollection|JsonResponse
     */
    public function index(SortSpecialityRequest $request, SpecialityService $service): SpecialityCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new SpecialityCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSpecialityRequest $request
     * @param SpecialityService $service
     * @return SpecialityResource
     */
    public function store(StoreSpecialityRequest $request, SpecialityService $service) : SpecialityResource
    {
        $speciality = $service->create($request->validated());

        return new SpecialityResource($speciality);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return SpecialityResource
     */
    public function show(int $id): SpecialityResource
    {
        $speciality = Cache::remember(Speciality::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Speciality::findOrFail($id);
        });

        return new SpecialityResource($speciality);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param UpdateSpecialityRequest $request
     * @param SpecialityService $service
     * @param Speciality $speciality
     * @return SpecialityResource
     */
    public function update(UpdateSpecialityRequest $request, SpecialityService $service, Speciality $speciality) : SpecialityResource
    {
        $speciality = $service->update($request->validated(), $speciality->id);

        return new SpecialityResource($speciality);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param SpecialityService $service
     * @param Speciality $speciality
     * @return JsonResponse
     */
    public function destroy(SpecialityService $service, Speciality $speciality): JsonResponse
    {
        $service->destroy($speciality->id);

        return $this->sendResponse();
    }
}
