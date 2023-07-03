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
     * @return Student $model
     */
    public function create(array $attributes): Student
    {
        $student = $this->model;

        $student->fill($attributes);
        $student->save();

        $this->addToMatterMost($student);

        return $student;
    }
}
