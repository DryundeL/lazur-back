<?php

namespace App\Modules\Admin\Student\Services;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Modules\Admin\Models\Admin;
use App\Services\BaseService;
use App\Traits\Authorizable;

class StudentService extends BaseService
{
    use Authorizable;

    public function __construct(Student $student) {
        $this->model = $student;
    }
}
