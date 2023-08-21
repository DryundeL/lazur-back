<?php

namespace App\Modules\Employee\JournalDate\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Models\JournalDate;
use App\Modules\Employee\JournalDate\Requests\SortJournalDateRequest;
use App\Modules\Employee\JournalDate\Resource\JournalDateCollection;
use App\Modules\Employee\JournalDate\Resource\JournalDateResource;
use App\Modules\Employee\JournalDate\Service\JournalDateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class JournalDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortJournalDateRequest $request
     * @param JournalDateService $service
     * @return JournalDateCollection|JsonResponse
     */
    public function index(SortJournalDateRequest $request, JournalDateService $service): JournalDateCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new JournalDateCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JournalDateResource
     */
    public function show(int $id): JournalDateResource
    {
        $group = Cache::remember(JournalDate::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return JournalDate::findOrFail($id);
        });

        return new JournalDateResource($group);
    }
}
