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
     * @return Employee $model
     */
    public function create(array $attributes): Employee
    {
        $employee = $this->model;

        $employee->fill($attributes);
        $employee->save();

        $this->addToMatterMost($employee);

        return $employee;
    }
}
