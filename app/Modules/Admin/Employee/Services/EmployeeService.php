<?php

namespace App\Modules\Admin\Employee\Services;

use App\Models\Employee;
use App\Services\BaseService;
use App\Traits\Authorizable;

class EmployeeService extends BaseService
{
    use Authorizable;

    public function __construct(Employee $employee)
    {
        $this->model = $employee;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return array $model
     */
    public function createEmployee(array $attributes): array
    {
        $employee = $this->model;

        $employee->fill($attributes);
        $employee->save();

        return ['employee' => $employee, 'mm_status' => $this->addToMatterMost($employee)];
    }
}
