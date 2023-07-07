<?php

namespace App\Modules\Admin\Student\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Models\Group;
use App\Models\Student;
use App\Modules\Admin\Student\Requests\SortStudentRequest;
use App\Modules\Admin\Student\Requests\UpdateStudentRequest;
use App\Modules\Admin\Student\Resources\StudentCollection;
use App\Modules\Admin\Student\Resources\StudentResource;
use App\Modules\Admin\Student\Requests\StoreStudentRequest;
use App\Modules\Admin\Student\Services\StudentService;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortStudentRequest $request
     * @param StudentService $service
     * @return StudentCollection|JsonResponse
     */
    public function index(SortStudentRequest $request, StudentService $service): JsonResponse|StudentCollection
    {
        $subQueryArray = [];
        $filters = $request->validated();

        if (isset($filters['group_id'])) {

            $subQueryArray = [
                'filterModel' => new Group,
                'external_table' => 'group_student',
                'condition_id' => $filters['group_id']
            ];

            unset($filters['group_id']);
        }

        $responseArray = $service->search($filters, ['name' => 'first_name, last_name, patronymic_name'], $subQueryArray);

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
     * @param StoreStudentRequest $request
     * @param StudentService $service
     * @return StudentResource
     */
    public function store(StoreStudentRequest $request, StudentService $service) : StudentResource
    {
        $student = $service->create($request->validated());

        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $student
     * @return StudentResource
     */
    public function show(Student $student): StudentResource
    {
        return new StudentResource($student);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param UpdateStudentRequest $request
     * @param StudentService $service
     * @param Student $student
     * @return StudentResource
     */
    public function update(UpdateStudentRequest $request, StudentService $service, Student $student) : StudentResource
    {
        $student = $service->update($request->validated(), $student->id);

        return new StudentResource($student);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param StudentService $service
     * @param Student $student
     * @return JsonResponse
     */
    public function destroy(StudentService $service, Student $student): JsonResponse
    {
        $service->destroy($student->id);

        return $this->sendResponse();
    }
}
