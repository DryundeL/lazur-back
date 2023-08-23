<?php

namespace App\Modules\Employee\Journal\Controllers;

use App\Http\Controllers\BaseController as Controller;
use App\Models\Journal;
use App\Modules\Employee\Journal\Requests\SortJournalRequest;
use App\Modules\Employee\Journal\Requests\StoreJournalRequest;
use App\Modules\Employee\Journal\Resources\JournalCollection;
use App\Modules\Employee\Journal\Resources\JournalResource;
use App\Modules\Employee\Journal\Services\JournalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortJournalRequest $request
     * @param JournalService $service
     * @return JournalCollection|JsonResponse
     */
    public function index(SortJournalRequest $request, JournalService $service): JournalCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new JournalCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreJournalRequest $request
     * @param JournalService $service
     * @return JournalResource
     */
    public function store(StoreJournalRequest $request, JournalService $service): JournalResource
    {
        $group = $service->create($request->validated());

        return new JournalResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JournalResource
     */
    public function show(int $id): JournalResource
    {
        $group = Cache::remember(Journal::getCacheKey($id), Carbon::now()->addMinutes(10), function () use ($id) {
            return Journal::findOrFail($id);
        });

        return new JournalResource($group);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreJournalRequest $request
     * @param JournalService $service
     * @param Journal $journal
     * @return JournalResource
     */
    public function update(StoreJournalRequest $request, JournalService $service, Journal $journal): JournalResource
    {
        $journal = $service->update($request->validated(), $journal->id);

        return new JournalResource($journal);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param JournalService $service
     * @param Journal $journal
     * @return JsonResponse
     */
    public function destroy(JournalService $service, Journal $journal): JsonResponse
    {
        $service->destroy($journal->id);

        return $this->sendResponse();
    }
}
