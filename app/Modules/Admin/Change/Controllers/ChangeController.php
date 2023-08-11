<?php

namespace App\Modules\Admin\Change\Controllers;

use App\Http\Controllers\BaseController as Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Models\Change;
use App\Modules\Admin\Change\Requests\SortChangeRequest;
use App\Modules\Admin\Change\Requests\StoreChangeRequest;
use App\Modules\Admin\Change\Resources\ChangeCollection;
use App\Modules\Admin\Change\Resources\ChangeResource;
use App\Modules\Admin\Change\Services\ChangeService;

class ChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortChangeRequest $request
     * @param ChangeService $service
     * @return ChangeCollection|JsonResponse
     */
    public function index(SortChangeRequest $request, ChangeService $service): ChangeCollection|JsonResponse
    {
        $filters = $request->validated();

        $responseArray = $service->search($filters);

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new ChangeCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreChangeRequest $request
     * @param ChangeService $service
     * @return ChangeResource
     */
    public function store(StoreChangeRequest $request, ChangeService $service) : ChangeResource
    {
        $change = $service->create($request->validated());

        return new ChangeResource($change);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return ChangeResource
     */
    public function show(int $id): ChangeResource
    {
        $change = Cache::remember(Change::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Change::findOrFail($id);
        });

        return new ChangeResource($change);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreChangeRequest $request
     * @param ChangeService $service
     * @param Change $change
     * @return ChangeResource
     */
    public function update(StoreChangeRequest $request, ChangeService $service, Change $change): ChangeResource
    {
        $change = $service->update($request->validated(), $change->id);

        return new ChangeResource($change);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param ChangeService $service
     * @param Change $change
     * @return JsonResponse
     */
    public function destroy(ChangeService $service, Change $change): JsonResponse
    {
        $service->destroy($change->id);

        return $this->sendResponse();
    }
}
