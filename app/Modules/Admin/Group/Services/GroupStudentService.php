<?php

namespace App\Modules\Admin\Group\Services;

use App\Models\Group;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;

class GroupStudentService extends BaseService
{
    public function __construct(Group $group)
    {
        $this->model = $group;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $attributes
     * @param int $groupId
     * @return Model $model
     */
    public function createGroupStudents(array $attributes, int $groupId): Model
    {
        $group = $this->find($groupId);
        $group->students()->attach($attributes['students_ids']);

        return $group;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param array $attributes
     * @param int $id
     * @return Model $group
     */
    public function updateGroupStudents(array $attributes, int $id): Model
    {
        $group = $this->find($id);
        if ($attributes['students_ids']) {
            $group->students()->sync($attributes['students_ids']);
            $group->load('students');
        } else {
            $group->students()->detach();
        }

        return $group;
    }

}
