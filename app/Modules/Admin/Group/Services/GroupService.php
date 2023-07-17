<?php

namespace App\Modules\Admin\Group\Services;

use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Group;

class GroupService extends BaseService
{
    public function __construct(Group $group)
    {
        $this->model = $group;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @return Group $model
     */
    public function create(array $attributes): Group
    {
        $group = $this->model;

        $group->fill($attributes);
        $group->employee()->associate($attributes['employee_id']);
        $group->speciality()->associate($attributes['speciality_id']);
        $group->save();
        $group->semesters()->attach($attributes['semesters_ids']);

        return $group;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return Model $group
     */
    public function update(array $attributes, int $id): Model
    {
        $group = $this->find($id);
        $group->fill($attributes);
        $group->employee()->associate($attributes['employee_id']);
        $group->speciality()->associate($attributes['speciality_id']);
        $group->save();

        if ($attributes['semesters_ids']) {
            $group->semesters()->sync($attributes['semesters_ids']);
            $group->load('semesters');
        } else {
            $group->semesters()->detach();
        }

        return $group;
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $group = $this->find($id);
        $group->students()->detach();
        $group->semesters()->detach();
        Cache::forget($this->model->getCacheKey($id));
        return $group->delete();
    }
}
