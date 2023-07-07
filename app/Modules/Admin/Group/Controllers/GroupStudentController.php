<?php

namespace App\Modules\Admin\Group\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Group;
use App\Models\Student;
use App\Modules\Admin\Group\Requests\SortGroupStudentRequest;
use App\Modules\Admin\Group\Requests\StoreGroupStudentRequest;
use App\Modules\Admin\Group\Resources\GroupResource;
use App\Modules\Admin\Group\Resources\StudentCollection;
use App\Modules\Admin\Group\Services\GroupStudentService;

class GroupStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortGroupStudentRequest $request
     * @param GroupStudentService $service
     * @param Group $group
     * @return StudentCollection|JsonResponse
     */
    public function index(SortGroupStudentRequest $request, GroupStudentService $service, Group $group): StudentCollection|JsonResponse
    {
        $subQueryArray = [
            'searchModel' => new Student,
            'external_table' => 'group_student',
            'condition_id' => $group->id
        ];

        $responseArray = $service->search($request->validated(),['name' => 'first_name, last_name, patronymic_name'], $subQueryArray);


        if (!isset($responseArray['objects'])) {
            return $this->sendResponse($responseArray);
        } else {
            $response = new StudentCollection($responseArray['objects']);
            $meta = $responseArray['meta'];

            return (isset($meta))
                ? $response->additional($meta)
                : $response;
        }
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
