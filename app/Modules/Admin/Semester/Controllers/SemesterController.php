<?php

namespace App\Modules\Admin\Semester\Controllers;

use App\Models\Semester;
use App\Modules\Admin\Semester\Requests\SortSemesterRequest;
use App\Modules\Admin\Semester\Requests\StoreSemesterRequest;
use App\Modules\Admin\Semester\Requests\UpdateSemesterRequest;
use App\Modules\Admin\Semester\Resources\SemesterCollection;
use App\Modules\Admin\Semester\Services\SemesterService;
use App\Modules\Admin\Semester\Resources\SemesterResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Speciality;


class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortSemesterRequest $request
     * @param SemesterService $service
     * @return SemesterCollection|JsonResponse
     */
    public function index(SortSemesterRequest $request, SemesterService $service): SemesterCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new SemesterCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSemesterRequest $request
     * @param SemesterService $service
     * @return SemesterResource
     */
    public function store(StoreSemesterRequest $request, SemesterService $service) : SemesterResource
    {
        $speciality = $service->create($request->validated());

        return new SemesterResource($speciality);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return SemesterResource
     */
    public function show(int $id): SemesterResource
    {
        $semester = Cache::remember(Semester::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Semester::findOrFail($id);
        });

        return new SemesterResource($semester);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param UpdateSemesterRequest $request
     * @param SemesterService $service
     * @param Semester $semester
     * @return SemesterResource
     */
    public function update(UpdateSemesterRequest $request, SemesterService $service, Semester $semester) : SemesterResource
    {
        $semester = $service->update($request->validated(), $semester->id);

        return new SemesterResource($semester);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param SemesterService $service
     * @param Semester $semester
     * @return JsonResponse
     */
    public function destroy(SemesterService $service, Semester $semester): JsonResponse
    {
        $service->destroy($semester->id);

        return $this->sendResponse();
    }
}
