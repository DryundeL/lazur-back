<?php

namespace App\Modules\Admin\Group\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Group;
use App\Modules\Admin\Group\Requests\SortGroupRequest;
use App\Modules\Admin\Group\Requests\StoreGroupRequest;
use App\Modules\Admin\Group\Resources\GroupCollection;
use App\Modules\Admin\Group\Resources\GroupResource;
use App\Modules\Admin\Group\Services\GroupService;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortGroupRequest $request
     * @param GroupService $service
     * @return GroupCollection|JsonResponse
     */
    public function index(SortGroupRequest $request, GroupService $service): GroupCollection|JsonResponse
    {
        $responseArray = $service->search($request->validated());

        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new GroupCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGroupRequest $request
     * @param GroupService $service
     * @return GroupResource
     */
    public function store(StoreGroupRequest $request, GroupService $service) : GroupResource
    {
        $group = $service->create($request->validated());

        return new GroupResource($group);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return GroupResource
     */
    public function show(Group $group): GroupResource
    {
        return new GroupResource($group);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreGroupRequest $request
     * @param GroupService $service
     * @param Group $group
     * @return GroupResource
     */
    public function update(StoreGroupRequest $request, GroupService $service, Group $group) : GroupResource
    {
        $group = $service->update($request->validated(), $group->id);

        return new GroupResource($group);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param GroupService $service
     * @param Group $group
     * @return JsonResponse
     */
    public function destroy(GroupService $service, Group $group): JsonResponse
    {
        $service->destroy($group->id);

        return $this->sendResponse();
    }
}
