<?php

namespace App\Modules\Admin\Audience\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Audience;
use App\Modules\Admin\Audience\Requests\SortAudienceRequest;
use App\Modules\Admin\Audience\Requests\StoreAudienceRequest;
use App\Modules\Admin\Audience\Requests\UpdateAudienceRequest;
use App\Modules\Admin\Audience\Resources\AudienceCollection;
use App\Modules\Admin\Audience\Resources\AudienceResource;
use App\Modules\Admin\Audience\Services\AudienceService;

class AudienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortAudienceRequest $request
     * @param AudienceService $service
     * @return AudienceCollection|JsonResponse
     */
    public function index(SortAudienceRequest $request, AudienceService $service): AudienceCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new AudienceCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAudienceRequest $request
     * @param AudienceService $service
     * @return AudienceResource
     */
    public function store(StoreAudienceRequest $request, AudienceService $service) : AudienceResource
    {
        $audience = $service->create($request->validated());

        return new AudienceResource($audience);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return AudienceResource
     */
    public function show(int $id): AudienceResource
    {
        $audience = Cache::remember(Audience::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Audience::findOrFail($id);
        });

        return new AudienceResource($audience);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param UpdateAudienceRequest $request
     * @param AudienceService $service
     * @param Audience $audience
     * @return AudienceResource
     */
    public function update(UpdateAudienceRequest $request, AudienceService $service, Audience $audience) : AudienceResource
    {
        $audience = $service->update($request->validated(), $audience->id);

        return new AudienceResource($audience);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param AudienceService $service
     * @param Audience $audience
     * @return JsonResponse
     */
    public function destroy(AudienceService $service, Audience $audience): JsonResponse
    {
        $service->destroy($audience->id);

        return $this->sendResponse();
    }
}
