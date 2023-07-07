<?php

namespace App\Modules\Admin\Group\Services;

use App\Models\Employee;
use App\Models\Group;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

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
        $group->save();

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
        $group->save();

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
        return $group->delete();
    }
}