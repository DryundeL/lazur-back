<?php

namespace App\Modules\Admin\Employee\Controllers;

use App\Models\Employee;
use App\Models\Student;
use App\Modules\Admin\Employee\Requests\StoreEmployeeRequest;
use App\Modules\Admin\Employee\Requests\UpdateEmployeeRequest;
use App\Modules\Admin\Employee\Resources\EmployeeResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController as Controller;
use App\Modules\Admin\Employee\Services\EmployeeService;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return PracticeCollection|Response
     */
    public function index()
    {
        return 123;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @param EmployeeService $service
     * @return EmployeeResource
     */
    public function store(StoreEmployeeRequest $request, EmployeeService $service) : EmployeeResource
    {
        $student = $service->create($request->validated());

        return new EmployeeResource($student);
    }

    /**
     * Display the specified resource.
     *
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }

    /**
     * Update a newly created resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param EmployeeService $service
     * @param Employee $employee
     * @return EmployeeResource
     */
    public function update(UpdateEmployeeRequest $request, EmployeeService $service, Employee $employee) : EmployeeResource
    {
        $employee = $service->update($request->validated(), $employee->id);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param EmployeeService $service
     * @param Employee $employee
     * @return JsonResponse
     */
    public function destroy(EmployeeService $service, Employee $employee): JsonResponse
    {
        $service->destroy($employee->id);

        return $this->sendResponse();
    }
}
