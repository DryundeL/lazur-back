<?php

namespace App\Modules\Admin\Group\Controllers;

use App\Models\Group;
use App\Modules\Admin\Group\Requests\SortGroupRequest;
use App\Modules\Admin\Group\Requests\SortGroupStudentRequest;
use App\Modules\Admin\Group\Requests\StoreGroupRequest;
use App\Modules\Admin\Group\Requests\StoreGroupStudentRequest;
use App\Modules\Admin\Group\Requests\UpdateGroupRequest;
use App\Modules\Admin\Group\Resources\GroupCollection;
use App\Modules\Admin\Group\Resources\GroupResource;
use App\Modules\Admin\Group\Resources\GroupStudentCollection;
use App\Modules\Admin\Group\Services\GroupService;
use App\Modules\Admin\Group\Services\GroupStudentService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;

class GroupStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortGroupStudentRequest $request
     * @param GroupStudentService $service
     * @param Group $group
     * @return GroupResource|JsonResponse
     */
    public function index(SortGroupStudentRequest $request, GroupStudentService $service, Group $group): GroupResource|JsonResponse
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreGroupStudentRequest $request
     * @param GroupStudentService $service
     * @param Group $group
     * @return GroupResource
     */
    public function store(StoreGroupStudentRequest $request, GroupStudentService $service, Group $group) : GroupResource
    {
        $group = $service->createGroupStudents($request->validated(), $group->id);

        return new GroupResource($group);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param StoreGroupStudentRequest $request
     * @param GroupStudentService $service
     * @param Group $group
     * @return GroupResource
     */
    public function update(StoreGroupStudentRequest $request, GroupStudentService $service, Group $group) : GroupResource
    {
        $group = $service->updateGroupStudents($request->validated(), $group->id);

        return new GroupResource($group);
    }

}
