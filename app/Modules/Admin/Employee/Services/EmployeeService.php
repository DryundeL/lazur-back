<?php

namespace App\Modules\Admin\Employee\Services;

use App\Models\Employee;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Modules\Admin\Models\Admin;
use App\Services\BaseService;
use App\Traits\Authorizable;

class EmployeeService extends BaseService
{
    use Authorizable;

    public function __construct(Employee $employee) {
        $this->model = $employee;
    }
}
