<?php

namespace App\Modules\Admin\Semester\Services;

use App\Models\Semester;
use App\Services\BaseService;
use App\Traits\Authorizable;
use Illuminate\Support\Facades\Cache;

class SemesterService extends BaseService
{
    use Authorizable;

    public function __construct(Semester $semester)
    {
        $this->model = $semester;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $semester = $this->find($id);
        $semester->groups()->detach();
        Cache::forget($this->model->getCacheKey($id));
        return $semester->delete();
    }

}
