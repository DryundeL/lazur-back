<?php

namespace App\Modules\Admin\Student\Services;

use App\Models\Student;
use App\Services\BaseService;
use App\Traits\Authorizable;

class StudentService extends BaseService
{
    use Authorizable;

    public function __construct(Student $student)
    {
        $this->model = $student;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return array $model
     */
    public function createStudent(array $attributes): array
    {
        $student = $this->model;

        $student->fill($attributes);
        $student->save();

        return ['student' => $student, 'mm_status' => $this->addToMatterMost($student)];
    }
}
