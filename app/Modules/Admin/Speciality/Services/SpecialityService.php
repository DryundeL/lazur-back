<?php

namespace App\Modules\Admin\Speciality\Services;

use App\Models\Speciality;
use App\Services\BaseService;
use App\Traits\Authorizable;
use Illuminate\Support\Facades\Cache;

class SpecialityService extends BaseService
{
    use Authorizable;

    public function __construct(Speciality $speciality)
    {
        $this->model = $speciality;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $speciality = $this->find($id);

        $speciality->groups()->update(['speciality_id' => null]);
        $speciality->disciplines()->detach();
        Cache::forget($this->model->getCacheKey($id));

        return $speciality->delete();
    }

}
