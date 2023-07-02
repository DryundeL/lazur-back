<?php

namespace App\Modules\Admin\Student\Controllers;

use App\Models\Student;
use App\Modules\Admin\Student\Requests\UpdateStudentRequest;
use App\Modules\Admin\Student\Resources\StudentResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Modules\Admin\Student\Requests\StoreStudentRequest;
use App\Modules\Admin\Student\Services\StudentService;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param SortPracticeRequest $request
     * @return PracticeCollection|Response
     */
    public function index()
    {
        return 123;
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
