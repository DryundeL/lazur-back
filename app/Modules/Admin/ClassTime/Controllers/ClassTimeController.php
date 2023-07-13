<?php

namespace App\Modules\Admin\ClassTime\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Modules\Admin\ClassTime\Requests\SortClassTimeRequest;
use App\Modules\Admin\ClassTime\Requests\StoreClassTimeRequest;
use App\Modules\Admin\ClassTime\Resources\ClassTimeCollection;
use App\Modules\Admin\ClassTime\Resources\ClassTimeResource;
use App\Modules\Admin\ClassTime\Services\ClassTimeService;
use App\Modules\Admin\Models\ClassTime;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ClassTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortClassTimeRequest $request
     * @param ClassTimeService $service
     * @return ClassTimeCollection|JsonResponse
     */
    public function index(SortClassTimeRequest $request, ClassTimeService $service): ClassTimeCollection|JsonResponse
    {
        $filters = $request->validated();

        $responseArray = $service->search($filters);

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new ClassTimeCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreClassTimeRequest $request
     * @param ClassTimeService $service
     * @return ClassTimeResource
     */
    public function store(StoreClassTimeRequest $request, ClassTimeService $service): ClassTimeResource
    {
        $student = $service->create($request->validated());

        return new ClassTimeResource($student);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ClassTimeResource
     */
    public function show(int $id): ClassTimeResource
    {
        $classTime = Cache::remember(ClassTime::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return ClassTime::findOrFail($id);
        });

        return new ClassTimeResource($classTime);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreClassTimeRequest $request
     * @param ClassTimeService $service
     * @param ClassTime $classTime
     * @return ClassTimeResource
     */
    public function update(StoreClassTimeRequest $request, ClassTimeService $service, ClassTime $classTime): ClassTimeResource
    {
        $classTime = $service->update($request->validated(), $classTime->id);

        return new ClassTimeResource($classTime);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param ClassTimeService $service
     * @param ClassTime $classTime
     * @return JsonResponse
     */
    public function destroy(ClassTimeService $service, ClassTime $classTime): JsonResponse
    {
        $service->destroy($classTime->id);

        return $this->sendResponse();
    }
}
