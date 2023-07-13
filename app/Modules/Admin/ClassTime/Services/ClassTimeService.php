<?php

namespace App\Modules\Admin\ClassTime\Services;

use App\Models\ClassTime;
use App\Services\BaseService;

class ClassTimeService extends BaseService
{

    public function __construct(ClassTime $classTime)
    {
        $this->model = $classTime;
    }
}
